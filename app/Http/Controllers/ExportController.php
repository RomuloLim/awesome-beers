<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ExportController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Auth::user()->exports()->paginate);
    }
}
