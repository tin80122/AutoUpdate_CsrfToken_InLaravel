<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TokenController extends Controller
{
    public function refresh_token()
    {
        return ['status'=>'success','token'=>csrf_token()];
    }
}
