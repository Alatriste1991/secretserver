<?php

namespace App\Models;

use CodeIgniter\Model;

class CreateModel extends Model
{

    protected $table = 'secrets';

    protected $allowedFields = ['hash', 'secretText','createdAt','expiresAt','remainingViews'];

    public function checkHash($hash){

        return $this->where("hash", $hash)->findAll();
    }


    public function insertSecret($data){

        return $this->insert($data);
    }


    public function getSecretbyHash($hash){

        date_default_timezone_set("Europe/Budapest");

        $data = $this->where("hash", $hash)->findAll()[0];

        if(!empty($data)){

            return $data;

        }else{

            return false;

        }

    }

    public function updateHash($hash,$remainingviews){

        $this->set('remainingViews', $remainingviews -1);
        $this->where("hash", $hash)->update();

        return $this->where("hash", $hash)->findAll()[0];
    }
}