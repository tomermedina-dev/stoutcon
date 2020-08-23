<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBalanceSheetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('balance_sheet', function (Blueprint $table) {
            $table->id();
            $table->string('period');
            $table->integer('trial_balanace_id');
            $table->string('account');
            $table->integer('type'); //Assets,Liabilities,Equity,Revenue or Income,Expenses
            $table->integer('category')->nullable();// Current Assets,Fixed Asset |  Current Liabilities | Owners Equity
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
        Schema::dropIfExists('balance_sheet');
    }
}
