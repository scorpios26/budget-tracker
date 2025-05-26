<?php

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
        Schema::table('expenses', function (Blueprint $table) {
        // Remove old category column
        $table->dropColumn('category');

        // Add new category_id column (nullable for now)
        $table->unsignedBigInteger('category_id')->nullable()->after('amount');

        // Optional: Add foreign key constraint
        $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('expenses', function (Blueprint $table) {
        // Revert the changes
        $table->dropForeign(['category_id']);
        $table->dropColumn('category');
        $table->string('category')->nullable();
    });
    }
};
