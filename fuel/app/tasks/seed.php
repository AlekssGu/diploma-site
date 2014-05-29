<?php 
#seeding data

namespace Fuel\Tasks;

class Seed 
{
	public static function run()
	{            
            //Datu masīvs, ko ielikt datubāzē
            $external_users = array(
                "1" => array(
                        "name" => "Aleksandrs",
                        "surname" => "Gusevs",
                        "person_code" => "271192-10015",
                        "person_type" => "F",
                        "pri_street" => "Rīgas iela",
                        "pri_house" => "4/2",
                        "pri_flat" => "2",
                        "pri_district" => "Liepājas novads",
                        "pri_postcode" => "LV-3401",
                        "pri_city" => "Liepāja",
                        "sec_street" => "Rīgas iela",
                        "sec_house" => "4/2",
                        "sec_flat" => "2",
                        "sec_district" => "Liepājas novads",
                        "sec_postcode" => "LV-3401",
                        "sec_city" => "Liepāja",
                        "client_number" => "12345678",
                        "mobile_phone" => "+371 29826904"
                    ),
                
                "2" => array(
                        "name" => "Sarmīte",
                        "surname" => "Jēkabsone",
                        "person_code" => "123456-12345",
                        "person_type" => "F",
                        "pri_street" => "Interesantā iela",
                        "pri_house" => "52",
                        "pri_flat" => "10",
                        "pri_district" => "Liepājas novads",
                        "pri_postcode" => "LV-3401",
                        "pri_city" => "Liepāja",
                        "sec_street" => "Interesantā iela",
                        "sec_house" => "52",
                        "sec_flat" => "10",
                        "sec_district" => "Liepājas novads",
                        "sec_postcode" => "LV-3401",
                        "sec_city" => "Liepāja",
                        "client_number" => "11111111",
                        "mobile_phone" => "+371 29826904"
                    ),
                
                "3" => array(
                        "name" => "Demo",
                        "surname" => "Lietotājs",
                        "person_code" => "123456-12345",
                        "person_type" => "F",
                        "pri_street" => "Rīgas iela",
                        "pri_house" => "90",
                        "pri_flat" => "17",
                        "pri_district" => "Liepājas novads",
                        "pri_postcode" => "LV-3401",
                        "pri_city" => "Liepāja",
                        "sec_street" => "Rīgas iela",
                        "sec_house" => "90",
                        "sec_flat" => "17",
                        "sec_district" => "Liepājas novads",
                        "sec_postcode" => "LV-3401",
                        "sec_city" => "Liepāja",
                        "client_number" => "22222222",
                        "mobile_phone" => "+371 29826904"
                    ),
                
                "4" => array(
                        "name" => "Andis",
                        "surname" => "Dejus",
                        "person_code" => "123456-10015",
                        "person_type" => "F",
                        "pri_street" => "Klaipēdas iela",
                        "pri_house" => "190",
                        "pri_flat" => "1",
                        "pri_district" => "Liepājas novads",
                        "pri_postcode" => "LV-3401",
                        "pri_city" => "Liepāja",
                        "sec_street" => "Jelgavas iela",
                        "sec_house" => "23",
                        "sec_flat" => "9",
                        "sec_district" => "Liepājas novads",
                        "sec_postcode" => "LV-3401",
                        "sec_city" => "Liepāja",
                        "client_number" => "33333333",
                        "mobile_phone" => "+371 29826904"
                    ),
                
                "5" => array(
                        "name" => "Mārtiņš",
                        "surname" => "Ķemme",
                        "person_code" => "123456-12345",
                        "person_type" => "F",
                        "pri_street" => "Rīgas iela",
                        "pri_house" => "9",
                        "pri_flat" => "12",
                        "pri_district" => "Liepājas novads",
                        "pri_postcode" => "LV-3401",
                        "pri_city" => "Liepāja",
                        "sec_street" => "Rīgas iela",
                        "sec_house" => "9",
                        "sec_flat" => "12",
                        "sec_district" => "Liepājas novads",
                        "sec_postcode" => "LV-3401",
                        "sec_city" => "Liepāja",
                        "client_number" => "44444444",
                        "mobile_phone" => "+371 29826904"
                    ),
            );
            
		foreach ($external_users as $key => $user) {
                    
                        $exists = \DB::select()
                                ->from('external_users')
                                ->where('client_number','=',$user['client_number'])
                                ->as_object()
                                ->execute()
                                ->as_array();
                        
                        if(array_filter($exists))
                        {
                            echo "Client " . preg_replace('/[^a-zA-Z0-9.]/','',iconv('UTF-8', 'ASCII//TRANSLIT', $user['name'])) . " " . preg_replace('/[^a-zA-Z0-9.]/','',iconv('UTF-8', 'ASCII//TRANSLIT', $user['surname'])) . " already exists!\n";
                        }
                        else
                        {
                            $p = \Model_External_User::forge();
                            $p -> name = $user['name'];
                            $p -> surname = $user['surname'];
                            $p -> person_code = $user['person_code'];
                            $p -> person_type = $user['person_type'];
                            $p -> pri_street = $user['pri_street'];
                            $p -> pri_house = $user['pri_house'];
                            $p -> pri_flat = $user['pri_flat'];
                            $p -> pri_district = $user['pri_district'];
                            $p -> pri_postcode = $user['pri_postcode'];
                            $p -> pri_city = $user['pri_city'];
                            $p -> sec_street = $user['sec_street'];
                            $p -> sec_house = $user['sec_house'];
                            $p -> sec_flat = $user['sec_flat'];
                            $p -> sec_district = $user['sec_district'];
                            $p -> sec_postcode = $user['sec_postcode'];
                            $p -> sec_city = $user['sec_city'];
                            $p -> client_number = $user['client_number'];
                            $p -> mobile_phone = $user['mobile_phone'];
                            $p->save();
                            echo "Saving " . preg_replace('/[^a-zA-Z0-9.]/','',iconv('UTF-8', 'ASCII//TRANSLIT', $user['name'])) . " " . preg_replace('/[^a-zA-Z0-9.]/','',iconv('UTF-8', 'ASCII//TRANSLIT', $user['surname'])) . "\n";
                            echo "Seeding done!\n\n";
                        }
		}
	}

	# Reset all records in certain model, eg:
	#	php oil r seed:reset 'projects';
	public static function reset($modelname='')
	{
		if ($modelname)
		{
			\DBUtil::truncate_table($modelname);

			echo "All records on model $modelname successfully reset";
		}
		else {
			// I dont know how to list all models so I can directly
			// reset all tables...
			// So you must specify the model name instead like
			// example above... :(
			echo "Please specify a model name!";
		}
	}
}