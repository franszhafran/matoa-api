<?php
namespace App\Services;

use App\Models\User;

class AuthenticationService {
    public function getUserById(int $id): User {
        return User::where('id', $id)->firstOrFail();
    }
}
