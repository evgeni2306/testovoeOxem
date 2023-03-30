<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'quantity',
        'external_id',

    ];
    protected $hidden = [
        'id',
        'updated_at',
        'categories'
    ];


    static function findByExternal(string $externalId): Model
    {
        return self::query()->where('external_id', '=', $externalId)->first();
    }

    static function connectToCategories(int $productId, array $categories): void
    {
        foreach ($categories as $item) {
            ProductCategory::query()->create(['product_id' => $productId, 'category_id' => $item]);
        }
    }

    static function deleteWithDependencies(string $externalId): void
    {
        $product = self::findByExternal($externalId);
        ProductCategory::query()->where('product_id', '=', $product->id)->delete();
        $product->delete();
    }

    public function categories(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'product_categories',
            'product_id', 'category_id');
    }

    static function getByCategory(string $categoryExternal): array
    {
        $category = Category::findByExternal($categoryExternal);
        $products = Product::query()->join('product_categories', 'product_id','=', 'products.id')
            ->select('products.id','name','description','price','quantity','external_id','products.created_at')
            ->where('category_id', '=', $category->id)->get()->all();
        foreach ($products as $item){
            $item->categories = $item->categories;
        }
        return $products;
    }

}
