<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayrollTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payroll', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->string('month_year')->nullable();
            $table->integer('days')->default(0)->nullable();
            $table->double('basic_salary',15,2)->default(0.00)->nullable();
            $table->integer('nights')->default(0)->nullable();
            $table->double('night_differencial',15,2)->default(0.00)->nullable();
            $table->double('total_basic_salary',15,2)->default(0.00)->nullable();
            $table->double('overtime',15,2)->default(0.00)->nullable();
            $table->double('benefits',15,2)->default(0.00)->nullable();
            $table->double('other_benefits',15,2)->default(0.00)->nullable();
            $table->double('gross_pay',15,2)->default(0.00)->nullable();
            $table->double('sss',15,2)->default(0.00)->nullable();
            $table->double('philhealth',15,2)->default(0.00)->nullable();
            $table->double('pag_ibig',15,2)->default(0.00)->nullable();
            $table->double('tax',15,2)->default(0.00)->nullable();
            $table->double('tardiness',15,2)->default(0.00)->nullable();
            $table->double('total_deduction',15,2)->default(0.00)->nullable();
            $table->double('net_pay',15,2)->default(0.00)->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        DB::statement("ALTER TABLE payroll ADD UNIQUE INDEX unique_duplicated_checker (user_id, month_year);");


        $query = "CREATE VIEW payslip AS 
                    SELECT 
                    t2.id as user_id,
                    t2.name,
                    t2.email,
                    t2.username,
                    t2.password,
                    t2.date_of_birth,
                    t2.address,
                    t2.mobile_number,
                    t2.employee_identification,
                    t2.position,
                    t2.department,
                    t2.project,
                    t2.location,
                    t2.start_date,
                    t2.end_date,
                    t2.work_status,
                    t2.biometric_id,
                    t2.biometric,
                    t1.id as payslip_id,
                    t1.month_year,
                    t1.days,
                    t1.basic_salary,
                    t1.nights,
                    t1.night_differencial,
                    t1.total_basic_salary,
                    t1.overtime,
                    t1.benefits,
                    t1.other_benefits,
                    t1.gross_pay,
                    t1.sss,
                    t1.philhealth,
                    t1.pag_ibig,
                    t1.tax,
                    t1.tardiness,
                    t1.total_deduction,
                    t1.net_pay
                    FROM payroll t1 
                    LEFT JOIN users t2 ON t1.user_id = t2.id ";

        DB::statement($query);

        


        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payroll');
        DB::statement('DROP VIEW payslip');
    }
}
