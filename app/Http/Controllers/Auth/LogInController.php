<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LogInRequest;
use Facades\App\Classes\Form\BlueprintsFactory;
use function view;

class LogInController extends Controller
{
    public function create() {
        $blueprints = BlueprintsFactory::make(new LogInRequest());
        return view('auth.login', compact('blueprints'));
    }
    
    public function store(LogInRequest $request) {
        $request->authenticate();
    }
}
