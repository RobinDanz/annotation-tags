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
        Schema::create('tag_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::table('tags', function (Blueprint $table) {
            $table->foreignId('tag_group_id')
                  ->nullable()
                  ->constrained('tag_groups')
                  ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

        Schema::table('tags', function (Blueprint $table) {
            $table->dropConstrainedForeignId('tag_group_id');
        });
        
        Schema::dropIfExists('tag_groups');
    }
};
