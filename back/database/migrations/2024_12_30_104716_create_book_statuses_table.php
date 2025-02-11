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
        Schema::create('book_statuses', function (Blueprint $table) {
            $table->unsignedTinyInteger('id')->primary()->autoIncrement();
            $table->string('name');
            $table->string('display_name');
        });

        DB::table('book_statuses')->insert([
            [
                'name' => 'not-filled',
                'display_name' => 'Не заполнял',
            ],
            [
                'name' => 'filled',
                'display_name' => 'Заполнил',
            ],
            [
                'name' => 'review',
                'display_name' => 'Правки',
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('book_statuses');
    }
};
