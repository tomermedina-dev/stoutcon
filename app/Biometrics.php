<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use DateTime;
use DB;
use Carbon\Carbon as Carbon;

class Biometrics extends Model
{

  protected $table = 'biologs';

  public static function get_records($month,$user,$bio){

  		$query = "SELECT 
					date_field,

					(SELECT biologs.TimeOnlyRecord as time FROM users INNER JOIN biologs ON users.biometric_id = biologs.IndRegID WHERE users.id = {$user} AND biologs.DateOnlyRecord =  date_field AND biologs.MachineNumber = {$bio} ORDER BY biologs.TimeOnlyRecord ASC  LIMIT 1) as first_time_in,

					(SELECT biologs.TimeOnlyRecord as time FROM users INNER JOIN biologs ON users.biometric_id = biologs.IndRegID WHERE users.id = {$user} AND biologs.DateOnlyRecord =  date_field AND biologs.MachineNumber = {$bio} ORDER BY biologs.TimeOnlyRecord ASC  LIMIT 1,1) as first_time_out,

					(SELECT biologs.TimeOnlyRecord as time FROM users INNER JOIN biologs ON users.biometric_id = biologs.IndRegID WHERE users.id = {$user} AND biologs.DateOnlyRecord =  date_field AND biologs.MachineNumber = {$bio} ORDER BY biologs.TimeOnlyRecord ASC  LIMIT 2,1) as second_time_in,

					(SELECT biologs.TimeOnlyRecord as time FROM users INNER JOIN biologs ON users.biometric_id = biologs.IndRegID WHERE users.id = {$user} AND biologs.DateOnlyRecord =  date_field AND biologs.MachineNumber = {$bio} ORDER BY biologs.TimeOnlyRecord ASC  LIMIT 3,1) as second_time_out,

					(SELECT biologs.TimeOnlyRecord as time FROM users INNER JOIN biologs ON users.biometric_id = biologs.IndRegID WHERE users.id = {$user} AND biologs.DateOnlyRecord =  date_field AND biologs.MachineNumber = {$bio} ORDER BY biologs.TimeOnlyRecord ASC  LIMIT 4,1) as third_time_in,

					(SELECT biologs.TimeOnlyRecord as time FROM users INNER JOIN biologs ON users.biometric_id = biologs.IndRegID WHERE users.id = {$user} AND biologs.DateOnlyRecord =  date_field AND biologs.MachineNumber = {$bio} ORDER BY biologs.TimeOnlyRecord ASC  LIMIT 5,1) as third_time_out,

					(SELECT TIMEDIFF((SELECT biologs.TimeOnlyRecord as time FROM users INNER JOIN biologs ON users.biometric_id = biologs.IndRegID WHERE users.id = {$user} AND biologs.DateOnlyRecord =  date_field AND biologs.MachineNumber = {$bio} ORDER BY biologs.TimeOnlyRecord DESC  LIMIT 1), (SELECT biologs.TimeOnlyRecord as time FROM users INNER JOIN biologs ON users.biometric_id = biologs.IndRegID WHERE users.id = {$user} AND biologs.DateOnlyRecord =  date_field AND biologs.MachineNumber = {$bio} ORDER BY biologs.TimeOnlyRecord ASC  LIMIT 1))) as time_diff,
					

					-- (SELECT  SUBTIME( (SELECT TIMEDIFF((SELECT biologs.TimeOnlyRecord as time FROM users INNER JOIN biologs ON users.biometric_id = biologs.IndRegID WHERE users.id = {$user} AND biologs.DateOnlyRecord =  date_field AND biologs.MachineNumber = {$bio} ORDER BY biologs.TimeOnlyRecord DESC  LIMIT 1), (SELECT biologs.TimeOnlyRecord as time FROM users INNER JOIN biologs ON users.biometric_id = biologs.IndRegID WHERE users.id = {$user} AND biologs.DateOnlyRecord =  date_field AND biologs.MachineNumber = {$bio} ORDER BY biologs.TimeOnlyRecord ASC  LIMIT 1))) , '09:00:00') ) as overtime_diff


					(SELECT  SUBTIME( (SELECT TIMEDIFF((SELECT biologs.TimeOnlyRecord as time FROM users INNER JOIN biologs ON users.biometric_id = biologs.IndRegID WHERE users.id = {$user} AND biologs.DateOnlyRecord =  date_field AND biologs.MachineNumber = {$bio} ORDER BY biologs.TimeOnlyRecord DESC  LIMIT 1), (SELECT biologs.TimeOnlyRecord as time FROM users INNER JOIN biologs ON users.biometric_id = biologs.IndRegID WHERE users.id = {$user} AND biologs.DateOnlyRecord =  date_field AND biologs.MachineNumber = {$bio} ORDER BY biologs.TimeOnlyRecord ASC  LIMIT 1))) , '09:00:00') ) as overtime_diff



					FROM
					(
					    SELECT
					        MAKEDATE(YEAR('{$month}'),1) +
					        INTERVAL (MONTH('{$month}')-1) MONTH +
					        INTERVAL daynum DAY date_field
					    FROM
					    (
					        SELECT t*10+u daynum
					        FROM
					            (SELECT 0 t UNION SELECT 1 UNION SELECT 2 UNION SELECT 3) A,
					            (SELECT 0 u UNION SELECT 1 UNION SELECT 2 UNION SELECT 3
					            UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7
					            UNION SELECT 8 UNION SELECT 9) B
					        ORDER BY daynum
					    ) AA
					) AAA
					WHERE MONTH(date_field) = MONTH('{$month}');
					";



	   	$servername = env("DB_HOST");
		$username = env("DB_USERNAME");
		$password = env("DB_PASSWORD");
		$dbname = env("DB_DATABASE");

		// Create connection
		$conn = mysqli_connect($servername, $username, $password, $dbname);
		// Check connection
		if (!$conn) {
		die("Connection failed: " . mysqli_connect_error());
		}

		$sql = $query;
		$result = mysqli_query($conn, $sql);

		$data = [];

		if (mysqli_num_rows($result) > 0) {
		// output data of each row
			while($row = mysqli_fetch_assoc($result)) {

				$object = new \stdClass();
				$object->date_field = $row['date_field'];
				$object->first_time_in = $row['first_time_in'];
				$object->first_time_out = $row['first_time_out'];
				$object->second_time_in = $row['second_time_in'];
				$object->second_time_out = $row['second_time_out'];
				$object->third_time_in = $row['third_time_in'];
				$object->third_time_out = $row['third_time_out'];
				$object->time_diff = $row['time_diff'];
				$object->overtime_diff = $row['overtime_diff'];
				$data[] = $object;

			}
		}


		mysqli_close($conn);


	


       return $data;

  }

}


// DELIMITER //
// CREATE PROCEDURE attendance(
// IN user_id INTEGER,
// IN bio_id INTEGER,
// IN month_year DATE)

// BEGIN

// SELECT 
// date_field,

// (SELECT biologs.TimeOnlyRecord as time FROM users INNER JOIN biologs ON users.biometric_id = biologs.IndRegID WHERE users.id = user_id AND biologs.DateOnlyRecord =  date_field AND biologs.MachineNumber = bio_id ORDER BY biologs.TimeOnlyRecord ASC  LIMIT 1) as first_time_in,

// (SELECT biologs.TimeOnlyRecord as time FROM users INNER JOIN biologs ON users.biometric_id = biologs.IndRegID WHERE users.id = user_id AND biologs.DateOnlyRecord =  date_field AND biologs.MachineNumber = bio_id ORDER BY biologs.TimeOnlyRecord ASC  LIMIT 1,1) as first_time_out,

// (SELECT biologs.TimeOnlyRecord as time FROM users INNER JOIN biologs ON users.biometric_id = biologs.IndRegID WHERE users.id = user_id AND biologs.DateOnlyRecord =  date_field AND biologs.MachineNumber = bio_id ORDER BY biologs.TimeOnlyRecord ASC  LIMIT 2,1) as second_time_in,

// (SELECT biologs.TimeOnlyRecord as time FROM users INNER JOIN biologs ON users.biometric_id = biologs.IndRegID WHERE users.id = user_id AND biologs.DateOnlyRecord =  date_field AND biologs.MachineNumber = bio_id ORDER BY biologs.TimeOnlyRecord ASC  LIMIT 3,1) as second_time_out,

// (SELECT biologs.TimeOnlyRecord as time FROM users INNER JOIN biologs ON users.biometric_id = biologs.IndRegID WHERE users.id = user_id AND biologs.DateOnlyRecord =  date_field AND biologs.MachineNumber = bio_id ORDER BY biologs.TimeOnlyRecord ASC  LIMIT 4,1) as third_time_in,

// (SELECT biologs.TimeOnlyRecord as time FROM users INNER JOIN biologs ON users.biometric_id = biologs.IndRegID WHERE users.id = user_id AND biologs.DateOnlyRecord =  date_field AND biologs.MachineNumber = bio_id ORDER BY biologs.TimeOnlyRecord ASC  LIMIT 5,1) as third_time_out,

// (SELECT TIMEDIFF((SELECT biologs.TimeOnlyRecord as time FROM users INNER JOIN biologs ON users.biometric_id = biologs.IndRegID WHERE users.id = user_id AND biologs.DateOnlyRecord =  date_field AND biologs.MachineNumber = bio_id ORDER BY biologs.TimeOnlyRecord DESC  LIMIT 1), (SELECT biologs.TimeOnlyRecord as time FROM users INNER JOIN biologs ON users.biometric_id = biologs.IndRegID WHERE users.id = user_id AND biologs.DateOnlyRecord =  date_field AND biologs.MachineNumber = bio_id ORDER BY biologs.TimeOnlyRecord ASC  LIMIT 1))) as time_diff,


// (SELECT  SUBTIME( (SELECT TIMEDIFF((SELECT biologs.TimeOnlyRecord as time FROM users INNER JOIN biologs ON users.biometric_id = biologs.IndRegID WHERE users.id = user_id AND biologs.DateOnlyRecord =  date_field AND biologs.MachineNumber = bio_id ORDER BY biologs.TimeOnlyRecord DESC  LIMIT 1), (SELECT biologs.TimeOnlyRecord as time FROM users INNER JOIN biologs ON users.biometric_id = biologs.IndRegID WHERE users.id = user_id AND biologs.DateOnlyRecord =  date_field AND biologs.MachineNumber = bio_id ORDER BY biologs.TimeOnlyRecord ASC  LIMIT 1))) , '09:00:00') ) as overtime_diff



// FROM
// (
//     SELECT
//         MAKEDATE(YEAR(month_year),1) +
//         INTERVAL (MONTH(month_year)-1) MONTH +
//         INTERVAL daynum DAY date_field
//     FROM
//     (
//         SELECT t*10+u daynum
//         FROM
//             (SELECT 0 t UNION SELECT 1 UNION SELECT 2 UNION SELECT 3) A,
//             (SELECT 0 u UNION SELECT 1 UNION SELECT 2 UNION SELECT 3
//             UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7
//             UNION SELECT 8 UNION SELECT 9) B
//         ORDER BY daynum
//     ) AA
// ) AAA
// WHERE MONTH(date_field) = MONTH(month_year);

// END;
// //
// DELIMITER ;