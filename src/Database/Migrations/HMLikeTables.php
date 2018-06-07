<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class HMLikeTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        /*
         * Getting Tables Information from the Config File
         */
        $TableNames = config('HelperModels.Structure.TableNames');
        Schema::create($TableNames['Likes'], function (Blueprint $table){
            $table->increments('ID');
            $table->string('Likeable_type');
            $table->bigInteger('Likeable_id')->unsigned();
            $table->string('Liker_type');
            $table->bigInteger('Liker_id')->unsigned();
            $table->boolean('Like')->default(config('HelperModels.Likes.Settings.DefaultSettings.Default'));
            $table->timestamp('CreatedAt')->nullable();
            $table->timestamp('UpdatedAt')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        /*
         * Getting information from the config file
         */
        $TableNames = config('HelperModels.Structure.TableNames');
        Schema::dropIfExists($TableNames['Likes']);
    }
}