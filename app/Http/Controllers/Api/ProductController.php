<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use function League\Flysystem\toArray;

class ProductController extends Controller
{
    public function create(Request $request): JsonResponse
    {
        $fields = $request->all();
        $validator = Validator::make($fields, [
            'name' => 'required|string|max:200',
            'description' => 'string|max:1000',
            'authKey' => 'required|string|exists:users,key',
            'external_id' => 'required|string|unique:products,external_id',
            'price' => 'required|numeric|gt:0',
            'quantity' => 'required|integer',
            'category_id' => 'array',
            'category_id.*' => 'integer|exists:categories,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 404, ['Content-Type' => 'string']);
        }
        $product = Product::query()->create($fields);
        Product::connectToCategories($product->id, $fields['category_id']);
        return response()->json($product->id, 200, ['Content-Type' => 'string']);
    }

    public function update(Request $request): JsonResponse
    {
        $fields = $request->all();
        $validator = Validator::make($fields, [
            'name' => 'required|string|max:200',
            'description' => 'string|max:1000',
            'authKey' => 'required|string|exists:users,key',
            'external_id' => 'required|string|exists:products,external_id',
            'price' => 'required|numeric|gt:0',
            'quantity' => 'required|integer',
            'category_id' => 'array',
            'category_id.*' => 'integer|exists:categories,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 404, ['Content-Type' => 'string']);
        }

        $product = Product::findByExternal($fields['external_id']);
        $product->update($fields);
        return response()->json(['message' => 'updated'], 200, ['Content-Type' => 'string']);
    }

    public function delete(Request $request): JsonResponse
    {
        $fields = $request->all();
        $validator = Validator::make($fields, [
            'authKey' => 'required|string|exists:users,key',
            'external_id' => 'required|string|exists:products,external_id',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 404, ['Content-Type' => 'string']);
        }
        Product::deleteWithDependencies($fields['external_id']);
        return response()->json(['message' => 'deleted'], 200, ['Content-Type' => 'string']);
    }

    public function concrete(Request $request): JsonResponse
    {
        $fields = $request->all();
        $validator = Validator::make($fields, [
            'external_id' => 'required|string|exists:products,external_id',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 404, ['Content-Type' => 'string']);
        }
        $product = Product::findByExternal($fields['external_id']);
        $product->categories = $product->categories;
        return response()->json($product, 200, ['Content-Type' => 'string']);
    }

    public function getByCategory(Request $request): JsonResponse
    {
        $fields = $request->all();
        $validator = Validator::make($fields, [
            'external_id' => 'required|string|exists:categories,external_id',
        ]);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 404, ['Content-Type' => 'string']);
        }
        $products = Product::getByCategory($fields['external_id']);
        return response()->json($products, 200, ['Content-Type' => 'string']);

    }

    public function list(Request $request): JsonResponse
    {
        $fields = $request->all();
        $validator = Validator::make($fields, [
            'page' => 'integer|gt:0',
            'sort' => 'string|ends_with:sortBy,sortByDesc',
            'field' => 'string|ends_with:price,created_at'
        ]);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 404, ['Content-Type' => 'string']);
        }
        $products = Product::paginationWithSort($fields);
        return response()->json($products, 200, ['Content-Type' => 'string']);
    }
}
