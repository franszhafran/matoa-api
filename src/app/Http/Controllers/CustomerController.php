<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\JWTService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Auth0\Login\Contract\Auth0UserRepository;

class CustomerController extends Controller
{
    public function profile(Request $request) {
        return $this->sendData($request->user->toArray());
    }

    public function update(Request $request) {
        $user = $request->user;
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->name = $request->name;
        return $this->sendData($request->user->toArray());
    }
}
