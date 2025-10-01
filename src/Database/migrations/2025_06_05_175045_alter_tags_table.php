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
        Schema::table('tags', function (Blueprint $table) {
            $table->dropForeign(['annotation_id']);
            $table->dropColumn('annotation_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tags', function (Blueprint $table) {
            $table->integer('annotation_id')->unsigned();
            $table->foreign('annotation_id')
                ->references('id')
                ->on('image_annotations')
                ->onDelete('cascade');
        });
    }
};
