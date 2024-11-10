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
        // Add lender and borrower columns to the loans table
        Schema::table('loans', function (Blueprint $table) {
            $table->foreignId('lender_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('borrower_id')->constrained('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            // Drop foreign key constraints
            $table->dropForeign(['lender_id']);
            $table->dropForeign(['borrower_id']);

            // Drop columns
            $table->dropColumn(['lender_id', 'borrower_id']);
        });
    }
};
