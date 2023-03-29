<?php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    const TABLE_NAME = "products";

    public function up(): void
    {
        Schema::create(self::TABLE_NAME, function (Blueprint $table) {
            $table->id();
            $table->string('name', 255)->nullable(false);
            $table->string('description', 255)->nullable(false);
            $table->decimal('price')->nullable(false);
            $table->integer('count')->nullable(false);
            $table->string('external_id')->nullable(false)->unique();
            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists(self::TABLE_NAME);
    }
};
