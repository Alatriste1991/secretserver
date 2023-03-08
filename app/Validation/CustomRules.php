<?php

namespace App\Validation;
use CodeIgniter\HTTP\IncomingRequest;

class CustomRules {

    public function future_date($date): bool{

        date_default_timezone_set("Europe/Budapest");
    
        $today = date('Y-m-d H:i:s');
    
        if($date < $today)
        {
            return false;
        }
        else{
            return true;
        }
    }

}

    
