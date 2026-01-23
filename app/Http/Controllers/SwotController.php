<?php

namespace App\Http\Controllers;

use App\Models\SwotProject;
use App\Models\SwotBoard;
use App\Models\SwotFinalizedStrategy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use SimpleSoftwareIO\QrCode\Facades\QrCode as QrCodeGenerator;
use Carbon\Carbon; // For date formatting
use App\Exports\SwotProjectExport;
use App\Exports\SwotProjectFullExport;
use Maatwebsite\Excel\Facades\Excel;


class SwotController extends Controller
{
    // Admin: Create new SWOT project
    public function create()
    {
        return view('swot.create');
    }

    // Admin: Store new SWOT project
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $project = SwotProject::create([
            'title' => $request->title,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('swot.admin', $project->id)
            ->with('success', 'SWOT Project created successfully!');
    }

    public function moveBoardItem(Request $request, SwotBoard $board)
    {
        $request->validate([
            'type' => 'required|in:strength,weakness,opportunity,threat',
        ]);

        // Ownership check (via project)
        if ($board->project->created_by !== Auth::id()) {
            abort(403);
        }

        $board->update([
            'type' => $request->type,
        ]);

        return response()->json([
            'success' => true,
        ]);
    }

    public function updateBoardContent(Request $request, SwotBoard $board)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        // Ownership check (via project)
        if ($board->project->created_by !== Auth::id()) {
            abort(403);
        }

        $board->update([
            'content' => $request->content,
        ]);

        return response()->json([
            'success' => true,
            'content' => $board->content,
        ]);
    }

    // Admin: View project with QR code
    public function admin($id, Request $request)
    {
        $project = SwotProject::with('boards', 'finalize', 'finalizedStrategies')->findOrFail($id);

        if ($project->created_by !== Auth::id()) {
            abort(403);
        }

        // Prepare BSC strategies for display
        $bscStrategies = [];
        $dimensionTypes = [
            SwotFinalizedStrategy::TYPE_FINANCIAL,
            SwotFinalizedStrategy::TYPE_BENEFICIARIES,
            SwotFinalizedStrategy::TYPE_INTERNAL_PROCESSES,
            SwotFinalizedStrategy::TYPE_LEARNING_GROWTH,
        ];

        foreach ($dimensionTypes as $type) {
            $strategy = $project->finalizedStrategies->where('dimension_type', $type)->first();
            $bscStrategies[$type] = $strategy;
        }

        return view('swot.admin', compact('project', 'bscStrategies'));
    }

    public function exportExcel($id)
    {
        $project = SwotProject::with('finalize', 'finalizedStrategies')->findOrFail($id);

        if ($project->created_by !== auth()->id()) {
            abort(403);
        }

        $fileName = 'swot_project_' . $project->id . '_' . now()->format('Y_m_d') . '.xlsx';

        return Excel::download(new SwotProjectFullExport($project), $fileName);
    }


    public function display($id)
    {
        $project = SwotProject::with('boards')->findOrFail($id);

        if ($project->created_by !== Auth::id()) {
            abort(403);
        }

        // Deactivate only THIS user's projects
        SwotProject::where('created_by', Auth::id())
            ->where('id', '!=', $project->id)
            ->update(['is_active' => false]);

        // Activate current project
        $project->update(['is_active' => true]);

        $publicUrl = route('swot.public', $project->public_token);
        $qrCode = QrCodeGenerator::size(420)->generate($publicUrl);

        return view('swot.display', compact('project', 'qrCode', 'publicUrl'));
    }



    // Public: Show SWOT board (no auth required)
    public function show($token)
    {
        $project = SwotProject::where('public_token', $token)
            ->where('is_active', true)
            ->firstOrFail();

        return view('swot.public', compact('project'));
    }

    // Public: Initialize participant session
    public function initSession(Request $request, $token)
    {
        try {
            $request->validate([
                'participant_name' => 'required|string|max:15',
            ]);

            $project = SwotProject::where('public_token', $token)
                ->where('is_active', true)
                ->firstOrFail();

            $sessionKey = "swot_participant_{$project->id}";
            session([
                $sessionKey => [
                    'name' => $request->participant_name,
                    'ip' => $request->ip(),
                    'session_id' => session()->getId(),
                ]
            ]);

            return response()->json([
                'success' => true,
                'participant_name' => $request->participant_name
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'error' => $e->errors()['participant_name'][0] ?? 'Validation failed'
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'حدث خطأ غير متوقع، يرجى المحاولة مرة أخرى'
            ], 500);
        }
    }


    // Public: Add item to board
    public function addItem(Request $request, $token)
    {
        try {
            $request->validate([
                'type' => 'required|in:strength,weakness,opportunity,threat',
                'content' => 'required|string|max:55',
            ]);

            $project = SwotProject::where('public_token', $token)
                ->where('is_active', true)
                ->firstOrFail();

            $sessionKey = "swot_participant_{$project->id}";
            $participant = session($sessionKey);

            if (!$participant) {
                return response()->json([
                    'success' => false,
                    'error' => 'Session not initialized'
                ], 403);
            }

            $board = SwotBoard::create([
                'swot_project_id' => $project->id,
                'type' => $request->type,
                'content' => $request->content,
                'participant_name' => $participant['name'],
                'ip_address' => $request->ip(),
                'session_id' => session()->getId(),
            ]);

            return response()->json([
                'success' => true,
                'item' => $board
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'error' => collect($e->errors())->flatten()->first()
            ], 422);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'error' => 'Project not found or inactive'
            ], 404);
        } catch (\Exception $e) {
            \Log::error('SwotBoard addItem error', ['exception' => $e]);
            return response()->json([
                'success' => false,
                'error' => 'حدث خطأ غير متوقع، يرجى المحاولة مرة أخرى',
                'exception' => $e->getMessage(), // مؤقتًا للـ debug
            ], 500);
        }
    }



    // Public: Get all items (for auto-refresh)
    public function getItems($token)
    {
        try {
            $project = SwotProject::where('public_token', $token)
                ->where('is_active', true)
                ->firstOrFail();

            $items = [
                'strength' => $project->boards->where('type', 'strength')->values(),
                'weakness' => $project->boards->where('type', 'weakness')->values(),
                'opportunity' => $project->boards->where('type', 'opportunity')->values(),
                'threat' => $project->boards->where('type', 'threat')->values(),
            ];

            return response()->json([
                'success' => true,
                'items' => $items
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'items' => [
                    'strength' => [],
                    'weakness' => [],
                    'opportunity' => [],
                    'threat' => [],
                ],
                'error' => 'حدث خطأ في تحميل العناصر'
            ], 500);
        }
    }


    // Admin: List all projects
    public function index()
    {
        $projects = SwotProject::where('created_by', Auth::id())
            ->latest()
            ->get();

        return view('swot.index', compact('projects'));
    }

    public function finalize($id)
    {
        $project = SwotProject::with('boards', 'finalize', 'finalizedStrategies')->findOrFail($id);

        // Check if project has any boards
        if ($project->boards()->count() === 0) {
            return redirect()->back()->with('error', 'لا يمكن إنهاء المشروع قبل إضافة عناصر SWOT.');
        }

        // Authorization check
        if ($project->created_by !== Auth::id()) {
            abort(403);
        }

        // Check if already finalized (optional warning)
        if ($project->is_finalized) {
            session()->flash('warning', 'هذا المشروع تم إنهاؤه مسبقاً. يمكنك تعديل الاستراتيجيات.');
        }

        // Deactivate project
        $project->update(['is_active' => 0]);

        // Get or create finalize entry
        $finalize = $project->finalize ?? $project->finalize()->create([
            'created_by' => Auth::id()
        ]);

        // Aggregate SWOT data for better strategy writing
        $swotData = [
            'strengths' => $project->boards()->where('type', 'strength')->get(),
            'weaknesses' => $project->boards()->where('type', 'weakness')->get(),
            'opportunities' => $project->boards()->where('type', 'opportunity')->get(),
            'threats' => $project->boards()->where('type', 'threat')->get(),
        ];

        // Calculate statistics
        $stats = [
            'total_items' => $project->boards->count(),
            'participants' => $project->boards->groupBy('session_id')->count(),
            'strength_count' => $swotData['strengths']->count(),
            'weakness_count' => $swotData['weaknesses']->count(),
            'opportunity_count' => $swotData['opportunities']->count(),
            'threat_count' => $swotData['threats']->count(),
        ];

        // Prepare BSC dimension strategies
        $dimensionTypes = [
            SwotFinalizedStrategy::TYPE_FINANCIAL,
            SwotFinalizedStrategy::TYPE_BENEFICIARIES,
            SwotFinalizedStrategy::TYPE_INTERNAL_PROCESSES,
            SwotFinalizedStrategy::TYPE_LEARNING_GROWTH,
        ];

        $bscStrategies = [];
        foreach ($dimensionTypes as $type) {
            $strategy = $project->finalizedStrategies->where('dimension_type', $type)->first();
            $bscStrategies[$type] = $strategy ? [
                'strategic_goal' => $strategy->strategic_goal ?? '',
                'performance_indicator' => $strategy->performance_indicator ?? '',
                'initiatives' => $strategy->initiatives ?? [],
            ] : [
                'strategic_goal' => '',
                'performance_indicator' => '',
                'initiatives' => [],
            ];
        }

        return view('swot.finalize', compact('project', 'finalize', 'swotData', 'stats', 'bscStrategies'));
    }

    public function finalizeSave(Request $request, $id)
    {
        $project = SwotProject::with('finalize')->findOrFail($id);

        if ($project->created_by !== Auth::id()) {
            return response()->json([
                'success' => false,
                'error' => 'غير مصرح لك'
            ], 403);
        }

        // Validate finalize data
        $data = $request->validate([
            'summary' => 'nullable|string|max:5000',
            'strength_strategy' => 'nullable|string|max:2000',
            'weakness_strategy' => 'nullable|string|max:2000',
            'opportunity_strategy' => 'nullable|string|max:2000',
            'threat_strategy' => 'nullable|string|max:2000',
            'action_items' => 'nullable|array',
            'action_items.*.title' => 'required_with:action_items|string|max:255',
            'action_items.*.owner' => 'nullable|string|max:100',
            'action_items.*.priority' => 'nullable|in:High,Medium,Low',
            'action_items.*.deadline' => 'nullable|date|after_or_equal:today',
            // BSC Dimension strategies validation
            'bsc_strategies' => 'nullable|array',
            'bsc_strategies.*.strategic_goal' => 'nullable|string|max:2000',
            'bsc_strategies.*.performance_indicator' => 'nullable|string|max:2000',
            'bsc_strategies.*.initiatives' => 'nullable|array',
            'bsc_strategies.*.initiatives.*' => 'nullable|string|max:500',
        ]);

        $finalize = $project->finalize;
        // Exclude bsc_strategies from finalize update (stored in separate table)
        $finalizeData = collect($data)->except('bsc_strategies')->toArray();
        $finalize->update($finalizeData);

        // Save BSC dimension strategies
        if ($request->has('bsc_strategies')) {
            foreach ($request->bsc_strategies as $dimensionType => $strategyData) {
                SwotFinalizedStrategy::updateOrCreate(
                    [
                        'swot_project_id' => $project->id,
                        'dimension_type' => $dimensionType,
                    ],
                    [
                        'strategic_goal' => $strategyData['strategic_goal'] ?? null,
                        'performance_indicator' => $strategyData['performance_indicator'] ?? null,
                        'initiatives' => $strategyData['initiatives'] ?? [],
                    ]
                );
            }
        }

        $project->update([
            'is_finalized' => true,
            'finalized_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تم حفظ التلخيص بنجاح'
        ]);
    }

}
