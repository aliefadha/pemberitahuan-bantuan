<?php

namespace App\Http\Controllers;

use App\Services\WhatsAppService;
use Illuminate\Http\Request;

class WhatsAppController extends Controller
{
    protected WhatsAppService $whatsAppService;

    public function __construct(WhatsAppService $whatsAppService)
    {
        $this->whatsAppService = $whatsAppService;
    }

    private function checkAdmin()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }
    }

    public function index()
    {
        $this->checkAdmin();
        $status = $this->whatsAppService->getStatus();
        $qrData = $this->whatsAppService->getQrCode();

        return view('whatsapp.index', [
            'status' => $status,
            'qrCode' => $qrData['qr'] ?? null,
            'isReady' => $qrData === null,
        ]);
    }

    public function status()
    {
        $this->checkAdmin();

        return response()->json($this->whatsAppService->getStatus());
    }

    public function qr()
    {
        $this->checkAdmin();
        $qrData = $this->whatsAppService->getQrCode();

        return response()->json($qrData);
    }

    public function restart()
    {
        $this->checkAdmin();
        $result = $this->whatsAppService->restart();

        return response()->json([
            'success' => $result,
            'message' => $result ? 'WhatsApp is restarting...' : 'Failed to restart WhatsApp',
        ]);
    }

    public function sendTest(Request $request)
    {
        $this->checkAdmin();
        $request->validate([
            'phone' => 'required|string',
            'message' => 'required|string',
        ]);

        $result = $this->whatsAppService->sendMessage(
            $request->phone,
            $request->message
        );
        $success = $result !== null;

        return response()->json([
            'success' => $success,
            'message' => $success ? 'Message sent successfully' : 'Failed to send message',
        ]);
    }
}
