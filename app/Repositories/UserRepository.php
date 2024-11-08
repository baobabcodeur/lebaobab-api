<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserRepository implements UserRepositoryInterface
{
    /**
     * Create a new user.
     *
     * @param array $data
     * @return User
     */
    public function create(array $data): User
    {
        // Hashing the password before storing it
        $data['password'] = bcrypt($data['password']);
        return User::create($data);
    }

    /**
     * Find a user by ID.
     *
     * @param int $id
     * @return User
     * @throws ModelNotFoundException
     */
    public function findById(int $id): User
    {
        return User::findOrFail($id);
    }

    /**
     * Update a user by ID.
     *
     * @param int $id
     * @param array $data
     * @return User
     * @throws ModelNotFoundException
     */
    public function update(int $id, array $data): User
    {
        $user = User::findOrFail($id);
        
        // Hashing the password again if it's being updated
        if (isset($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }

        $user->update($data);
        return $user;
    }

    /**
     * Delete a user by ID.
     *
     * @param int $id
     * @return bool
     * @throws ModelNotFoundException
     */
    public function delete(int $id): bool
    {
        $user = User::findOrFail($id);
        return $user->delete();
    }
}
