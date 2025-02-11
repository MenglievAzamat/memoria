<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cover_types', function (Blueprint $table) {
            $table->unsignedSmallInteger('id')->primary()->autoIncrement();
            $table->string('name', 20);
            $table->string('image_link');
        });

        $path = env('APP_URL') . '/storage/';

        DB::table('cover_types')->insert([
            [
                'name' => 'red',
                'image_link' => $path . 'red_book.png',
            ],
            [
                'name' => 'green',
                'image_link' => $path . 'green_book.png',
            ],
            [
                'name' => 'brown',
                'image_link' => $path . 'brown_book.png',
            ],
            [
                'name' => 'blue',
                'image_link' => $path . 'blue_book.png',
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cover_types');
    }
};
