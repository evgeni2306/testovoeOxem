<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api\Authentication;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

class RegistrationController extends Controller
{
    public function registration(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:200',
            'surname' => 'required|string|max:200',
            'email' => 'required|string|max:200|email|unique:users,email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 404, ['Content-Type' => 'string']);

        }
        $fields = $request->all();
        $fields['key'] = time();
        $user = User::query()->create($fields);
        return response()->json(['authKey' => $user['key']], 200, ['Content-Type' => 'string']);
    }
}
