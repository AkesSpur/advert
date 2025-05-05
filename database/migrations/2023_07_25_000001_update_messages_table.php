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
        Schema::table('messages', function (Blueprint $table) {
            // Drop existing columns
            $table->dropColumn('is_read');
            
            // Add new columns
            $table->foreignId('conversation_id')->after('id')->constrained()->onDelete('cascade');
            $table->timestamp('read_at')->nullable()->after('content');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropForeign(['conversation_id']);
            $table->dropColumn('conversation_id');
            $table->dropColumn('read_at');
            $table->boolean('is_read')->default(false)->after('content');
        });
    }
};