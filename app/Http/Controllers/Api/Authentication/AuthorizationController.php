<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api\Authentication;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthorizationController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|max:200,exists:users,email',
            'password' => 'required|string',
        ]);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 404, ['Content-Type' => 'string']);
        }
        if (Auth::attempt($request->all('email', 'password'))) {
            return response()->json(['authKey' => Auth::user()->key], 200, ['Content-Type' => 'string']);
        }
        return response()->json([['message' => 'Не удалось авторизоваться, проверьте правильность заполнения полей']], 404, ['Content-Type' => 'string']);
    }
}
