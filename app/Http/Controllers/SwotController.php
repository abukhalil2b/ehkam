<?php

namespace App\Http\Controllers;

use App\Models\SwotProject;
use App\Models\SwotBoard;
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

    // Admin: View project with QR code
    // في الكنترولر، تحديث method admin للتعامل مع أنواع التصدير المختلفة:
    public function admin($id, Request $request)
    {
        $project = SwotProject::with('boards', 'finalize')->findOrFail($id);

        if ($project->created_by !== Auth::id()) {
            abort(403);
        }

        return view('swot.admin', compact('project'));
    }

  public function exportExcel($id)
{
    $project = SwotProject::with('finalize')->findOrFail($id);

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
            session([$sessionKey => [
                'name' => $request->participant_name,
                'ip' => $request->ip(),
                'session_id' => session()->getId(),
            ]]);

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

        $project = SwotProject::with('boards', 'finalize')->findOrFail($id);

        if ($project->boards()->count() === 0) {
            return redirect()->back()->with('error', 'لا يمكن إنهاء المشروع قبل إضافة عناصر SWOT.');
        }


        if ($project->created_by !== Auth::id()) {
            abort(403);
        }

        $project->update(['is_active' => 0]);

        // Get or create finalize entry
        $finalize = $project->finalize ?? $project->finalize()->create([
            'created_by' => Auth::id()
        ]);

        return view('swot.finalize', compact('project', 'finalize'));
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

        $data = $request->validate([
            'summary' => 'nullable|string',
            'strength_strategy' => 'nullable|string',
            'weakness_strategy' => 'nullable|string',
            'threat_strategy' => 'nullable|string',
            'action_items' => 'nullable|array',
            'action_items.*.title' => 'required|string',
            'action_items.*.owner' => 'nullable|string',
            'action_items.*.priority' => 'nullable|string',
            'action_items.*.deadline' => 'nullable|date',
        ]);

        $finalize = $project->finalize;
        $finalize->update($data);

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
