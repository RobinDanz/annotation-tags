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
            $table->dropColumn('value');
        });

        Schema::table('annotations_tags', function (Blueprint $table) {
            $table->dropForeign(['annotation_id']);
            $table->renameColumn('annotation_id', 'image_annotation_id');
            $table->foreign('image_annotation_id')->references('id')->on('image_annotations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tags', function (Blueprint $table) {
            $table->string('value');
        });

        Schema::table('annotations_tags', function (Blueprint $table) {
            $table->dropForeign(['image_annotation_id']);
            $table->renameColumn('image_annotation_id', 'annotation_id');
            $table->foreign('annotation_id')->references('id')->on('image_annotations')->onDelete('cascade');
        });
    }
};
