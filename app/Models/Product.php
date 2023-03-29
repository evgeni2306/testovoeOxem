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
        'external_id'

    ];
    protected $hidden=[
        'id',
        'updated_at'
    ];

    static function findByExternal(string $externalId): Model
    {
        return self::query()->where('external_id', '=', $externalId)->first();
    }

    static function connectToCategories(int $productId, array $categories):void
    {
        foreach ($categories as $item){
            ProductCategory::query()->create(['product_id'=>$productId,'category_id'=>$item]);
        }
    }
}
