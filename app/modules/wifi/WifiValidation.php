<?php
namespace App\Validation;

use App\Lib\Response;

class WifiValidation {

    public static function Validate($data,$update=false){
        $response = new Response();
        

        if($update){
            $key = 'id';
            if(empty($data[$key])) {
                $response->errors[$key][] = 'Este campo es obligatorio';
            }            
        }


       if(!$update){
            $key = 'mac';
            if(empty($data[$key])) {
                $response->errors[$key][] = 'Este campo es obligatorio';
            }         
        } 


        
        $key = 'name';
        if(empty($data[$key])) {
            $response->errors[$key][] = 'Este campo es obligatorio';
        }


        
        $response->setResponse(count($response->errors) === 0);


        return $response;
    }


    public static function ValidateProcess($data){
        $response = new Response();
        

        if($update){
            $key = 'id';
            if(empty($data[$key])) {
                $response->errors[$key][] = 'Este campo es obligatorio';
            }            
        }

        
        $response->setResponse(count($response->errors) === 0);


        return $response;
    }


    public static function ValidateCrack($data){
        $response = new Response();
        

        if($update){
            $key = 'id';
            if(empty($data[$key])) {
                $response->errors[$key][] = 'Este campo es obligatorio';
            }            
        }

        
        $response->setResponse(count($response->errors) === 0);


        return $response;
    }


    

}



