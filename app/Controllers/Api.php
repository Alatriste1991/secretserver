<?php

namespace App\Controllers;

use CodeIgniter\API\ResponseTrait;

class Api extends BaseController
{

    use ResponseTrait;

    public function show($hash)
    {
        $model = new \App\Models\CreateModel();

        $request = service('request');
        
        $type = $this->checkHeader($request);

        if($type == false){
            return $this->failForbidden('Invalid Accept Header format');
        }

        $data = $model->getSecretbyHash($hash);

        if($data == false){
            return $this->setResponseFormat($type)->fail( 'Not found secret by hash', 404);
        }else{

            $rules = [
                'remainingViews'    => [
                    'rules'         => 'greater_than[0]',
                    'errors'        => [
                        'greater_than[0]'   => 'You can\'t see your secret, because the maximum number of views allowed has been reached',
                    ],
                ],
                'expiresAt'         => [
                    'rules'         => 'future_date',
                    'errors'        => [
                        'future_date'   => 'You can\'t see your secret, because viewing time has expired',
                    ], 
                ],
            ];
        }

        if (!$this->validateData($data, $rules)) {

            return $this->setResponseFormat($type)->fail( $this->validator->getErrors(), 405);
        
        }else{

            $response_data = $model->updateHash($hash,$data['remainingViews']);

            $response_data['secretText'] = $this->cryptSecret($data['secretText'],true);

            return $this->setResponseFormat($type)->respond($response_data, 200);
        }
    }

    public function create()
    {

        date_default_timezone_set("Europe/Budapest");

        $request = service('request');
        
        $type = $this->checkHeader($request);


        if($type == false){
            return $this->failForbidden('Invalid Accept Header format');
        }
        if($request->getPost()){

            $data = [
                'secret'            => $request->getPost('secret'),
                'expireAfterViews'  => $request->getPost( 'expireAfterViews'),
                'expireAfter'       => $request->getPost('expireAfter'),
            ];

            $rule = [
                'secret'            => 'required',
                'expireAfterViews'  => 'required|greater_than_equal_to[0]',
                'expireAfter'       => 'required|greater_than_equal_to[0]'
            ];

            
            if (!$this->validateData($data, $rule)) {

                return $this->setResponseFormat($type)->fail( $this->validator->getErrors(), 405);
            
            }else{
                $model = new \App\Models\CreateModel();

                $hash = $this->generateHash();

                if($data['expireAfter'] == '0'){
                    $final = '2999-12-31 23:59:59';
                }else{
                    $time = strtotime(date('Y-m-d H:i:s'));
                    $final = date("Y-m-d H:i:s", strtotime("+".$data['expireAfter']." minutes", $time));
                }
                
                $savedata = [
                    'hash'              => $hash,
                    'secretText'        => $this->cryptSecret($data['secret']),
                    'createdAt'         => date('Y-m-d H:i:s'),
                    'expiresAt'         => $final,
                    'remainingViews'    => $data['expireAfterViews'],
                ];

                $model->insertSecret($savedata);

                $savedata['secretText'] = $this->cryptSecret($savedata['secretText'],true);

                return $this->setResponseFormat($type)->respond($savedata, 200);
            }
        }

    }

    public function generateHash(){
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $string = array(); 
        $alphaLength = strlen($alphabet) - 1; 
        for ($i = 0; $i <= 10; $i++) {
            $n = rand(0, $alphaLength);

            $string[] = $alphabet[$n];
        }
        $string = hash('sha256', implode($string));

        $model = new \App\Models\CreateModel();

        if($model->checkHash($string)){
            $this->generateHash();
        }else{
            return $string;
        }
    }


    public function checkHeader($request){
        switch ($request->getHeaderLine('Accept')) {
            case 'application/json':
                $type = 'json';
                break;
            case 'application/xml':
                $type = 'xml';
                break;
            default:
            $type = false;
        }

        return $type;
    }

    public function cryptSecret($string, $decrypt=false){
        $ciphering = "AES256";
        $options = 0;
        
        $iv = '1234567891011121';
        $key = "SecretServer";
        
        if($decrypt == false){

            $response = openssl_encrypt($string, $ciphering,$key, $options, $iv);

        }else{

            $response = openssl_decrypt($string, $ciphering, $key, $options, $iv);
        }

        return $response;
    }
}

