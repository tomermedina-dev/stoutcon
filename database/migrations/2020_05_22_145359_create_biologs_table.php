<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBiologsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('biologs', function (Blueprint $table) {
            $table->id();
            $table->integer('MachineNumber');
            $table->integer('IndRegID')->nullable();
            $table->integer('DwInOutMode');
            $table->datetime('DateTimeRecord');
            $table->date('DateOnlyRecord');
            $table->time('TimeOnlyRecord');
            $table->integer('status')->default(1);
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
        Schema::dropIfExists('biologs');
    }
}
