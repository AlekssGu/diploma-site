<?php 
#seeding data

namespace Fuel\Tasks;

class Creviews 
{

    	public static function run()
	{       
            $view_folder = APPPATH . 'migrations\db_objects';
            $views = array (
                    '1' => '\all_obj_services.sql',
                    '2' => '\all_readings.sql',
                    '3' => '\all_usr_requests.sql',
                    '4' => '\client_objects.sql',
                    '5' => '\last_readings.sql'
                );
            
            
            foreach($views as $view)
            {
                //$query = \DB::query(\Fuel::load($view_folder . $view)) -> execute();
                echo \Fuel::load($view_folder . $view);
                echo "\n";
                echo "\n";
                var_dump($view_folder);
                echo "\n";
                var_dump($view);
                echo "\n";
                echo "\n";
                echo "\n";
                echo "\n";
                echo "\n";
            }

        }
    
}