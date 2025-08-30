<?php

namespace App\Http\Controllers;

use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// 3|Miyl2WjigrpEvquSpMYXhI0BGjHNuAxtHMFytwQDa9a2e7d6

class AuthController extends Controller
{
    use HttpResponses;

    public function login(Request $request)
    {
        if(Auth::attempt($request->only('email', 'password'))){
            return $this->response('Authorized', 200, [
                'token' => $request->user()->createToken('invoice', ['invoice-index'])->plainTextToken
            ]);
        } else {
            return $this->response('Not authorized', 403);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return $this->response('Token revoked', 200);
    }
}
