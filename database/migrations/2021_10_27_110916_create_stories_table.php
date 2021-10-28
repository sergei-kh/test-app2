<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stories', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_from')->default(false);
            $table->unsignedBigInteger('storage_id');
            $table->unsignedBigInteger('target_id')->nullable();
            $table->foreign('storage_id')
                ->references('id')
                ->on('storages');
            $table->foreign('target_id')
                ->references('id')
                ->on('storages');
            $table->timestamp('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stories');
    }
}
