<?php

namespace App\Repositories;

use App\Models\Subscription;
use App\Repositories\Interfaces\SubscriptionRepositoryInterface;

class SubscriptionRepository implements SubscriptionRepositoryInterface
{
    public function create(array $data)
    {
        return Subscription::create($data);
    }

    public function findByUserId(int $userId)
    {
        return Subscription::where('user_id', $userId)->first();
    }

    public function update(int $id, array $data)
    {
        $subscription = Subscription::findOrFail($id);
        $subscription->update($data);
        return $subscription;
    }

    public function delete(int $id)
    {
        $subscription = Subscription::findOrFail($id);
        return $subscription->delete();
    }
}
