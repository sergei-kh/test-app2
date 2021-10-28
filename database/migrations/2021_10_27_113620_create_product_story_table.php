<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductStoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_story', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('story_id');
            $table->unsignedBigInteger('product_id');
            $table->foreign('story_id')
                ->references('id')
                ->on('stories');
            $table->foreign('product_id')
                ->references('id')
                ->on('products');
            $table->unsignedInteger('count')->default(0);
            $table->unsignedInteger('remainder')->default(0);
            $table->boolean('is_decreased')->default(false);
            $table->boolean('is_increased')->default(false);
            $table->boolean('is_deleted')->default(false);
            $table->boolean('is_created')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_story');
    }
}
