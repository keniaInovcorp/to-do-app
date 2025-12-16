<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;

abstract class Controller
{
    use AuthorizesRequests;

    /**
     * Get the authenticated user.
     *
     * @return User
     */
    protected function authUser(): User
    {
        /** @var User */
        return Auth::user();
    }
}
