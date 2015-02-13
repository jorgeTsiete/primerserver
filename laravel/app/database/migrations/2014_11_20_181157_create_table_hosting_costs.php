<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTableHostingCosts extends Migration {

      /**
       * Run the migrations.
       *
       * @return void
       */
      public function up()
      {
            Schema::create('hosting_costs', function(Blueprint $table) {
                  $table->increments('id');
                  $table->double("cost")->nullable();               
                  $table->string('currency')->nullable();
                  $table->string('concept')->nullable();
                  $table->integer('plan_id')->unsigned();
                  $table->foreign('plan_id')->references('id')->on('plans')->onDelete('cascade')->onUpdate('cascade');
                  $table->boolean('active');
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
            Schema::drop('hosting_costs');
      }

}
