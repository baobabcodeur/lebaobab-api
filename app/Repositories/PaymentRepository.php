<?php

namespace App\Repositories;

use App\Models\Payment;
use App\Repositories\Interfaces\PaymentRepositoryInterface;

class PaymentRepository implements PaymentRepositoryInterface
{
    public function create(array $data)
    {
        return Payment::create($data);
    }

    public function findById(int $id)
    {
        return Payment::findOrFail($id);
    }

    public function updateStatus(int $id, string $status)
    {
        $payment = Payment::findOrFail($id);
        $payment->status = $status;
        $payment->save();
        return $payment;
    }

    public function delete(int $id)
    {
        $payment = Payment::findOrFail($id);
        return $payment->delete();
    }
}
