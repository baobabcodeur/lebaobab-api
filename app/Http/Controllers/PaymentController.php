<?php
namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'amount' => 'required|numeric',
            'project_id' => 'required|exists:projects,id',
        ]);

        $payment = Payment::create($request->all());
        return response()->json($payment, 201);
    }

    public function show($project_id): JsonResponse
    {
        $payments = Payment::where('project_id', $project_id)->get();
        return response()->json($payments, 200);
    }

    public function createEscrow(Request $request): JsonResponse
    {
        // Logique pour créer un paiement en escroquerie
        return response()->json(['message' => 'Escrow payment created'], 201);
    }
}
