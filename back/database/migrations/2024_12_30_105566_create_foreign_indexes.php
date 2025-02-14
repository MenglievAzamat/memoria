<?php

use App\Models\Book;
use App\Models\BookStatus;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->foreign('user_id')
                ->references('id')
                ->on('users');

            $table->foreign('book_status_id')
                ->references('id')
                ->on('book_statuses');
        });

        Schema::table('questionnaires', function (Blueprint $table) {
            $table->foreign('book_id')
                ->references('id')
                ->on('books');
        });

        Schema::table('chapters', function (Blueprint $table) {
            $table->foreign('book_id')
                ->references('id')
                ->on('books');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->dropForeignIdFor(User::class);
            $table->dropForeignIdFor(BookStatus::class);
        });

        Schema::table('questionnaires', function (Blueprint $table) {
            $table->dropForeignIdFor(Book::class);
        });

        Schema::table('chapters', function (Blueprint $table) {
            $table->dropForeignIdFor(Book::class);
        });
    }
};
