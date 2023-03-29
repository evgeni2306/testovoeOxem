<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    public function create(Request $request): JsonResponse
    {
        $fields = $request->all();
        $validator = Validator::make($fields, [
            'name' => 'required|string|max:200|unique:categories,name',
            'authKey' => 'required|string|exists:users,key',
            'external_id' => 'required|string|unique:categories,external_id',
            'parent_id' => 'integer|exists:categories,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 404, ['Content-Type' => 'string']);
        }
        $category = Category::query()->create($fields);
        return response()->json($category->id, 200, ['Content-Type' => 'string']);
    }

    public function update(Request $request): JsonResponse
    {
        $fields = $request->all();
        $validator = Validator::make($fields, [
            'name' => 'required|string|max:200|unique:categories,name',
            'authKey' => 'required|string|exists:users,key',
            'external_id' => 'required|string|exists:categories,external_id',
            'parent_id' => 'integer|exists:categories,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 404, ['Content-Type' => 'string']);
        }

        $category = Category::findByExternal($fields['external_id']);
        $category->update($fields);
        return response()->json(['message' => 'updated'], 200, ['Content-Type' => 'string']);
    }

    public function delete(Request $request):JsonResponse
    {
        $fields = $request->all();
        $validator = Validator::make($fields, [
            'authKey' => 'required|string|exists:users,key',
            'external_id' => 'required|string|exists:categories,external_id',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 404, ['Content-Type' => 'string']);
        }
        Category::deleteWithDependencies($fields['external_id']);
        return response()->json(['message' => 'deleted'], 200, ['Content-Type' => 'string']);
    }

    public function list():JsonResponse
    {
        $categoris = Category::all();
        return response()->json($categoris, 200, ['Content-Type' => 'string']);
    }
}
