<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayoutDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payout_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('referenceId')->nullable();
            $table->string('bankAccount')->nullable();
            $table->string('ifsc')->nullable();
            $table->string('beneId')->nullable();
            $table->string('amount')->nullable();
            $table->string('status')->nullable();
            $table->string('utr')->nullable();
            $table->string('addedOn')->nullable();
            $table->string('processedOn')->nullable();
            $table->string('transferMode')->nullable();
            $table->string('acknowledged')->nullable();
            $table->string('tds')->nullable();
            $table->timestamps();
            
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
        Schema::dropIfExists('payout_details');
    }
}
