<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class JsonOperation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jsonOperation';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $categories = json_decode(Storage::disk('local')->get('categories.json'), true);
        $products = json_decode(Storage::disk('local')->get('products.json'), true);
        foreach ($categories as $item) {
            $item['external_id'] = (string)$item['external_id'];
            $validator = Validator::make($item, [
                'name' => 'required|string|max:200|unique:categories,name',
                'external_id' => 'required|string|unique:categories,external_id',
            ]);
            if ($validator->fails()) {
                $this->error('Валидация категории с external_id=' . $item['external_id'] . ' не пройдена, создание категорий остановлено');
                break;
            }
            $category = Category::query()->updateOrCreate(['external_id' => $item['external_id'], 'name' => $item['name']]);
        }
        foreach ($products as $item) {
            $item['external_id'] = (string)$item['external_id'];
            $validator = Validator::make($item, [
                'name' => 'required|string|max:200',
                'external_id' => 'required|string|unique:products,external_id',
                'price' => 'required|numeric|gt:0',
                'quantity' => 'required|integer',
                'category_id' => 'array',
                'category_id.*' => 'integer|exists:categories,id'
            ]);
            if ($validator->fails()) {
                $this->error('Валидация продукта с external_id=' . $item['external_id'] . ' не пройдена, создание продуктов остановлено');
                break;
            }
            $categoryList = $item['category_id'];
            unset($item['category_id']);
            $product = Product::query()->updateOrCreate($item);
            Product::connectToCategories($product->id, $categoryList);
        }
        $this->line('Команда была выполнена');
    }
}
