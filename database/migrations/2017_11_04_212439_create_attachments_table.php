<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attachments', function (Blueprint $table) {
            $table->increments('id');
            $table->text('path')->nullable();
            $table->string('name')->nullable();
            $table->string('type')->nullable();
            $table->integer('size')->nullable();
            $table->timestamps();
        });

        Schema::table('attachments', function (Blueprint $table) {
            $table->integer('user_id')->unsigned()->index()->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {   
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Schema::dropIfExists('attachments');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}
