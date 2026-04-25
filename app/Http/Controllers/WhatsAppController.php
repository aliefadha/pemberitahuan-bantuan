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

    public function index()
    {
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
        return response()->json($this->whatsAppService->getStatus());
    }

    public function qr()
    {
        $qrData = $this->whatsAppService->getQrCode();

        return response()->json($qrData);
    }

    public function restart()
    {
        $result = $this->whatsAppService->restart();

        return response()->json([
            'success' => $result,
            'message' => $result ? 'WhatsApp is restarting...' : 'Failed to restart WhatsApp',
        ]);
    }

    public function sendTest(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'message' => 'required|string',
        ]);

        $result = $this->whatsAppService->sendMessage(
            $request->phone,
            $request->message
        );

        return response()->json([
            'success' => $result,
            'message' => $result ? 'Message sent successfully' : 'Failed to send message',
        ]);
    }
}
