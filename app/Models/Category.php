<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'external',
        'parent_id',
        'external_id'
    ];
    protected $hidden = [
        'id',
        'updated_at'
    ];

    static function findByExternal(string $externalId): Model
    {
        return self::query()->where('external_id', '=', $externalId)->first();
    }

    static function deleteWithDependencies(string $externalId): void
    {
        $category = self::findByExternal($externalId);
        ProductCategory::query()->where('category_id', '=', $category->id)->delete();
        $category->delete();
    }

    public function products(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_categories', 'category_id', 'product_id');
    }

}
