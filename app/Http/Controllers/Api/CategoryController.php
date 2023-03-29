<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function create(Request $request)
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
        $category = Category::query()->create(['name' => $fields['name'], 'external_id' => $fields['external_id']]);
        if (isset($fields['parent_id'])) {
            $category->parent_id = $fields['parent_id'];
            $category->save();
        }
        return response()->json($category->id, 200, ['Content-Type' => 'string']);
    }

    public function update(Request $request)
    {

    }

    public function delete(Request $request)
    {

    }
}
