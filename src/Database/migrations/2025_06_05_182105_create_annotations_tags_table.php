<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('annotations_tags', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('annotation_id');
            $table->unsignedBigInteger('tag_id');
            $table->string('value')->nullable();

            $table->timestamps();

            $table->foreign('annotation_id')->references('id')->on('image_annotations')->onDelete('cascade');
            $table->foreign('tag_id')->references('id')->on('tags')->onDelete('cascade');
            $table->unique(['annotation_id', 'tag_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('annotations_tags');
    }
};
