<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Linksdata extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('linksdata')) {
            Schema::create('linksdata', function (Blueprint $table) {

                $table->increments('id');
                $table->string('url');
                $table->string('category_id');
                $table->string('title')->nullable();
                $table->string('description')->nullable();
                $table->string('category')->nullable();
                $table->string('review')->nullable();
                $table->string('ip')->nullable();
                $table->string('ipregion')->nullable();
                $table->string('datasource')->nullable();
                $table->string('status')->default(1);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('linksdata');
    }
}
