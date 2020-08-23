<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrialBalanceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trial_balance', function (Blueprint $table) {
            $table->id();
            $table->string('period');
            $table->string('account');
            $table->integer('tool'); //debit | credit
            $table->integer('type'); //Assets,Liabilities,Equity,Revenue or Income,Expenses
            $table->integer('category')->nullable();
            $table->integer('status'); //permanent/temporary
            $table->double('amount',15,2)->default(0.00)->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('trial_balance');
     // /   DB::statement('DROP PROCEDURE  get_trial_balance');
    }
}
