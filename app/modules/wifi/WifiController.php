<?php
namespace App\Controller;

use App\Lib\Response,
App\Lib\Auth;

class WifiController
{
    private $response;
    
    public function __construct($model)
    {
        $this->response = new Response();
        $this->model = $model;
    }



    public function register($data,$file)
    {
        if(is_null($data['process']) or empty($file['pcap'])){
            $data['process'] = 0;
        }
        if (!empty($file['pcap'])) {
            $newfile = $file['pcap'];
            if ($newfile->getError() === UPLOAD_ERR_OK) {
                $namefile = md5(uniqid());
                $uploadFileName = $newfile->getClientFilename();
                $parts = explode('.', $uploadFileName);
                $namefile .= ".".$parts['1'];

                try {
                    if ($newfile->moveTo("../uploads/$namefile")) {
                        throw new \Exception();
                    }                    
                } catch (\Exception $e) {
                    //var_dump($e);
                    return $this->response->SetResponse(false,$e->getMessage());
                }
                return $this->model->register($data['name'],$data['mac'],$data['audit'],$namefile);
            }
        }else{
            return $this->model->register($data['name'],$data['mac'],$data['audit']);
        }
    
    }

    public function update($data,$file)
    {
        $check_user_wifi = $this->model->getDataByIdAndUser($data['id'],'1');
        if($check_user_wifi->response == true && !empty($check_user_wifi->result)){
            if (!empty($file['pcap'])) {
                $newfile = $file['pcap'];
                if ($newfile->getError() === UPLOAD_ERR_OK) {
                    $namefile = md5(uniqid());
                    $uploadFileName = $newfile->getClientFilename();
                    $parts = explode('.', $uploadFileName);
                    $namefile .= ".".$parts['1'];
                    try {
                        if ($newfile->moveTo("../uploads/$namefile")) {
                            throw new \Exception();
                        }                    
                        if(file_exists("../uploads/".$check_user_wifi->result->file)){
                            unlink("../uploads/".$check_user_wifi->result->file);
                        }
                    } catch (\Exception $e) {
                        return $this->response->SetResponse(false,$e->getMessage());
                    }
                    return $this->model->update($data['id'],'1',$data['name'],$namefile);
                }
            }else{
                return $this->model->update($data['id'],'1',$data['name'],$check_user_wifi->result->file);
            }
        }else{
            return $this->response->SetResponse(false,'Esa wifi no es tuya mamon');
        }
    
    }



    public function activeProcess($data,$id_user)
    {
        $check_user_wifi = $this->model->getDataByIdAndUser($data['id'],$id_user);
        if($check_user_wifi->response == true && !empty($check_user_wifi->result)){
            if($check_user_wifi->result->process == 0){
                return $this->model->activeProcess($data['id'],$id_user);
            }elseif ($check_user_wifi->result->process == 1) {
                return $this->response->SetResponse(false,'Ya esta en cola de procesamiento');
            }elseif ($check_user_wifi->result->process == 2) {
                return $this->response->SetResponse(false,'Ya esta procesado');
            }     
        }else{
            return $this->response->SetResponse(false,'Esa wifi no es tuya mamon');
        }
    }

    public function getelementprocess()
    {
        return $this->model->getelementprocess();
    }


    public function getDataByIdUser($id,$id_audit){
        $data= $this->model->getDataByIdUser($id,$id_audit);
        return $data;
        //var_dump($data);
    }








}
