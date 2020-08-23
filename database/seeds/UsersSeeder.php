<?php

use Illuminate\Database\Seeder;
use Faker\Provider\ar_SA\Person;
use Faker\Provider\fr_FR\Address;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        $faker = Faker\Factory::create();
        $password = Hash::make('password');



        
        App\User::create([
            'name' => 'Administrator',
            'email' => 'admin@admin.com',
            'username' => 'admin',
            'role' => 1,
            'password' => $password,
 
        ]);

	    for($i = 0; $i < 120; $i++) {
	        App\User::create([
	        	'name' => $faker->name,
	            'email' => $faker->unique()->email,
	            'username' => $faker->unique()->userName,
	            'role' => 0,
	            'password' => $password,
	            'address' => $faker->address,
                'date_of_birth' => $faker->date,
                'start_date' => $faker->date,
                'work_status' => 'Regular Employee',
                'end_date' => $faker->date,
	            'employee_identification' => $faker->ein,
                'project' => 'Pilipinas Shell Petroleum Corp.',
                'location' => 'Tabangao, Batangas City',
	            'position' => $faker->jobTitle,
	            'department' => $faker->company,
	            'salary_amount' => '24950.40', //$faker->numberBetween(10000, 20000),
	            'mobile_number' => $faker->phoneNumber,
                'rate_per_hour' => '141.76',
                'night_differencial' => '14.18',
                'sss_contribution' => '800.00',
                'philhealth' => '374.26',
                'pag_ibig' => '100.00',
                'benefits' => '4050.00',
                'other_benefits' => '1274.26',
                'tax_withheld' => '823.48',

	        ]);
	    }


    }
}
