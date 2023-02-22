<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LogInRequest;

class LogInController extends Controller
{
    public function create() {
        
    }
    
    public function store(LogInRequest $request) {
        $request->authenticate();
    }
}
