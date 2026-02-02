<?php

namespace App\Http\Controllers;

use App\Models\PestleProject;
use App\Models\PestleBoard;
use App\Models\PestleFinalizedStrategy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use SimpleSoftwareIO\QrCode\Facades\QrCode as QrCodeGenerator;


class PestleController extends Controller
{
    // Admin: Create new PESTLE project
    public function create()
    {
        return view('pestle.create');
    }

    // Admin: Store new PESTLE project
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $project = PestleProject::create([
            'title' => $request->title,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('pestle.admin', $project->id)
            ->with('success', 'PESTLE Project created successfully!');
    }

    public function moveBoardItem(Request $request, PestleBoard $board)
    {
        $request->validate([
            'type' => 'required|in:political,economic,social,technological,legal,environmental',
        ]);

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

    public function updateBoardContent(Request $request, PestleBoard $board)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

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
    public function admin($id)
    {
        $project = PestleProject::with('items', 'finalize', 'finalizedStrategies')->findOrFail($id);

        if ($project->created_by !== Auth::id()) {
            abort(403);
        }

        // Prepare Strategies for display
        $strategies = [];
        $dimensionTypes = [
            'political',
            'economic',
            'social',
            'technological',
            'legal',
            'environmental'
        ];

        foreach ($dimensionTypes as $type) {
            $strategy = $project->finalizedStrategies->where('dimension_type', $type)->first();
            $strategies[$type] = $strategy;
        }

        return view('pestle.admin', compact('project', 'strategies'));
    }

    public function display($id)
    {
        $project = PestleProject::with('items')->findOrFail($id);

        if ($project->created_by !== Auth::id()) {
            abort(403);
        }

        // Deactivate only THIS user's projects
        PestleProject::where('created_by', Auth::id())
            ->where('id', '!=', $project->id)
            ->update(['is_active' => false]);

        // Activate current project
        $project->update(['is_active' => true]);

        $publicUrl = route('pestle.public', $project->public_token);
        $qrCode = QrCodeGenerator::size(300)->generate($publicUrl);

        return view('pestle.display', compact('project', 'qrCode', 'publicUrl'));
    }

    // Public: Show Board (no auth required)
    public function show($token)
    {
        $project = PestleProject::where('public_token', $token)
            ->where('is_active', true)
            ->firstOrFail();

        return view('pestle.public', compact('project'));
    }

    // Public: Initialize participant session
    public function initSession(Request $request, $token)
    {
        try {
            $request->validate([
                'participant_name' => 'required|string|max:15',
            ]);

            $project = PestleProject::where('public_token', $token)
                ->where('is_active', true)
                ->firstOrFail();

            $sessionKey = "pestle_participant_{$project->id}";
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
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Error initializing session'
            ], 500);
        }
    }

    // Public: Add item to board
    public function addItem(Request $request, $token)
    {
        try {
            $request->validate([
                'type' => 'required|in:political,economic,social,technological,legal,environmental',
                'content' => 'required|string|max:55',
            ]);

            $project = PestleProject::where('public_token', $token)
                ->where('is_active', true)
                ->firstOrFail();

            $sessionKey = "pestle_participant_{$project->id}";
            $participant = session($sessionKey);

            if (!$participant) {
                return response()->json([
                    'success' => false,
                    'error' => 'Session not initialized'
                ], 403);
            }

            $board = PestleBoard::create([
                'pestle_project_id' => $project->id,
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
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Error adding item'
            ], 500);
        }
    }

    // Public: Get all items (for auto-refresh)
    public function getItems($token)
    {
        try {
            $project = PestleProject::where('public_token', $token)
                ->where('is_active', true)
                ->firstOrFail();

            $items = [
                'political' => $project->items->where('type', 'political')->values(),
                'economic' => $project->items->where('type', 'economic')->values(),
                'social' => $project->items->where('type', 'social')->values(),
                'technological' => $project->items->where('type', 'technological')->values(),
                'legal' => $project->items->where('type', 'legal')->values(),
                'environmental' => $project->items->where('type', 'environmental')->values(),
            ];

            return response()->json([
                'success' => true,
                'items' => $items
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'items' => [],
                'error' => 'Error loading items'
            ], 500);
        }
    }

    // Admin: List all projects
    public function index()
    {
        $projects = PestleProject::where('created_by', Auth::id())
            ->latest()
            ->get();

        return view('pestle.index', compact('projects'));
    }

    public function finalize($id)
    {
        $project = PestleProject::with('items', 'finalize', 'finalizedStrategies')->findOrFail($id);

        if ($project->items()->count() === 0) {
            return redirect()->back()->with('error', 'Cannot finalize empty project.');
        }

        if ($project->created_by !== Auth::id()) {
            abort(403);
        }

        // Deactivate project
        $project->update(['is_active' => 0]);

        // Get or create finalize entry
        $finalize = $project->finalize ?? $project->finalize()->create([
            'created_by' => Auth::id()
        ]);

        // Aggregate Data
        $data = [
            'political' => $project->items->where('type', 'political'),
            'economic' => $project->items->where('type', 'economic'),
            'social' => $project->items->where('type', 'social'),
            'technological' => $project->items->where('type', 'technological'),
            'legal' => $project->items->where('type', 'legal'),
            'environmental' => $project->items->where('type', 'environmental'),
        ];

        // Prepare Strategies
        $strategies = [];
        $dimensionTypes = [
            'political',
            'economic',
            'social',
            'technological',
            'legal',
            'environmental'
        ];

        foreach ($dimensionTypes as $type) {
            $strategy = $project->finalizedStrategies->where('dimension_type', $type)->first();
            $strategies[$type] = $strategy ? [
                'strategic_goal' => $strategy->strategic_goal ?? '',
                'performance_indicator' => $strategy->performance_indicator ?? '',
                'initiatives' => $strategy->initiatives ?? [],
            ] : [
                'strategic_goal' => '',
                'performance_indicator' => '',
                'initiatives' => [],
            ];
        }

        return view('pestle.finalize', compact('project', 'finalize', 'data', 'strategies'));
    }

    public function finalizeSave(Request $request, $id)
    {
        $project = PestleProject::with('finalize')->findOrFail($id);

        if ($project->created_by !== Auth::id()) {
            return response()->json(['success' => false, 'error' => 'Unauthorized'], 403);
        }

        $data = $request->validate([
            'summary' => 'nullable|string|max:5000',
            'strategies' => 'nullable|array',
            'strategies.*.strategic_goal' => 'nullable|string|max:2000',
            'strategies.*.performance_indicator' => 'nullable|string|max:2000',
            'strategies.*.initiatives' => 'nullable|array',
        ]);

        $project->finalize->update(['summary' => $request->summary]);

        if ($request->has('strategies')) {
            foreach ($request->strategies as $type => $strategyData) {
                PestleFinalizedStrategy::updateOrCreate(
                    [
                        'pestle_project_id' => $project->id,
                        'dimension_type' => $type,
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
            'message' => 'Finalized successfully'
        ]);
    }
}
