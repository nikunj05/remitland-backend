<?php

namespace App\Http\Controllers;

use App\Http\Responses\ApiResponse;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    public function index(): JsonResponse
    {
        $users = User::latest()->get();

        return ApiResponse::success($users, 'Users retrieved successfully.');
    }
}