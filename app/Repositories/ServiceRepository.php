<?php

namespace App\Repositories;

use App\Models\Service;
use App\Repositories\Interfaces\ServiceRepositoryInterface;

class ServiceRepository implements ServiceRepositoryInterface
{
    public function all()
    {
        return Service::all();
    }

    public function create(array $data)
    {
        return Service::create($data);
    }

    public function findById(int $id)
    {
        return Service::findOrFail($id);
    }

    public function update(int $id, array $data)
    {
        $service = Service::findOrFail($id);
        $service->update($data);
        return $service;
    }

    public function delete(int $id)
    {
        $service = Service::findOrFail($id);
        return $service->delete();
    }
}
