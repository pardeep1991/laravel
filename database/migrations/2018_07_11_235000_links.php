<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Links extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('links',function (Blueprint $table){

            $table->increments('id');
            $table->string('url');
            $table->string('title')->nullable()->default('Unknown');
            $table->text('description')->nullable();
            $table->string('category')->nullable()->default('Unknown');
            $table->string('review')->nullable()->default('Unknown');
            $table->string('review_by')->nullable()->default('Unknown');
            $table->string('audit')->nullable()->default('Unknown');
            $table->string('JCLIB')->nullable()->default('Unknown');
            $table->string('ip')->nullable()->default('Unknown');
            $table->string('ipregion')->nullable()->default('Unknown');
            $table->string('datasource')->nullable()->default('Unknown');
            $table->string('status')->nullable()->default('1');
            $table->text('url_type')->nullable()->default('Unknown');
            $table->text('domain')->nullable()->default('Unknown');
            $table->text('url_level1')->nullable();
            $table->text('url_level2')->nullable();
            $table->text('url_level3')->nullable();
            $table->text('site_status')->nullable()->default('Unknown');
            $table->text('http_status')->nullable()->default('Unknown');
            $table->text('https_status')->nullable()->default('Unknown');
            $table->text('China_ip')->nullable()->default('Unknown');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('links');
    }
}
