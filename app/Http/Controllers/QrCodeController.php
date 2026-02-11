<?php

namespace App\Http\Controllers;

use App\Models\QrCode;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode as QrCodeGenerator;

class QrCodeController extends Controller
{
    public function index()
    {
        $qrCodes = QrCode::latest()->get();
        return view('qr.index', compact('qrCodes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:100',
            'content' => 'required|url|max:255',
        ]);

        QrCode::create([
            'title' => $request->title,
            'content' => $request->content,
            'written_by' => auth()->id(),
        ]);

        return redirect()->route('qr.index')->with('success', 'تم إنشاء رمز QR بنجاح');
    }

    public function show(QrCode $qr)
    {
        $qrImage = QrCodeGenerator::size(200)->generate($qr->content);
        return view('qr.show', compact('qr', 'qrImage'));
    }

    public function destroy(QrCode $qr)
    {
        $qr->delete();
        return back()->with('success', 'تم حذف رمز QR بنجاح');
    }
}
