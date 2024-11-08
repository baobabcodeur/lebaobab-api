<?php

namespace App\Repositories;

use App\Models\UrgentAd;
use App\Repositories\Interfaces\UrgentAdRepositoryInterface;

class UrgentAdRepository implements UrgentAdRepositoryInterface
{
    public function create(array $data)
    {
        return UrgentAd::create($data);
    }

    public function findById(int $id)
    {
        return UrgentAd::findOrFail($id);
    }

    public function delete(int $id)
    {
        $urgentAd = UrgentAd::findOrFail($id);
        return $urgentAd->delete();
    }
}
