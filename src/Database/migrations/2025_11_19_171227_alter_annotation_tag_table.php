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
        Schema::table('annotations_tags', function (Blueprint $table) {
            $table->dropForeign(['tag_id']);
            $table->dropForeign(['image_annotation_id']);
        });

        Schema::rename('annotations_tags', 'tag_annotations');

        Schema::table('tag_annotations', function (Blueprint $table) {
            $table->foreign('tag_id')->references('id')->on('tags')->onDelete('cascade');
            $table->foreign('image_annotation_id')->references('id')->on('image_annotations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tag_annotations', function (Blueprint $table) {
            $table->dropForeign(['tag_id']);
            $table->dropForeign(['image_annotation_id']);
        });

        Schema::rename('tag_annotations', 'annotations_tags');

        Schema::table('annotations_tags', function (Blueprint $table) {
            $table->foreign('tag_id')->references('id')->on('tags')->onDelete('cascade');
            $table->foreign('image_annotation_id')->references('id')->on('image_annotations')->onDelete('cascade');
        });
    }
};
