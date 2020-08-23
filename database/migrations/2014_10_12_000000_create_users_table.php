<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique()->nullable();
            $table->string('username')->unique()->nullable();
            $table->string('password')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('address')->nullable();
            $table->string('mobile_number')->nullable();
            $table->string('employee_identification')->nullable();
            $table->string('position')->nullable();
            $table->string('department')->nullable();
            $table->string('work_status')->nullable();
            $table->string('project')->nullable();
            $table->string('location')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            // $table->date('period')->nullable();
            $table->double('rate_per_hour',15,2)->default(0.00)->nullable();
            $table->double('night_differencial',15,2)->default(0.00)->nullable();
            $table->double('salary_amount',15,2)->default(0.00)->nullable();
            $table->double('sss_contribution',15,2)->default(0.00)->nullable();
            $table->double('philhealth',15,2)->default(0.00)->nullable();
            $table->double('pag_ibig',15,2)->default(0.00)->nullable();
            $table->double('tax_withheld',15,2)->default(0.00)->nullable(); 
            $table->double('benefits',15,2)->default(0.00)->nullable(); 
            $table->double('other_benefits',15,2)->default(0.00)->nullable();
            $table->string('biometric_id')->nullable();
            $table->integer('biometric')->default(1);
            $table->integer('role')->default(0);
            $table->integer('status')->default(1);
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
