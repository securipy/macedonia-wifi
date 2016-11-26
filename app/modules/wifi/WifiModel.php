<?php
namespace App\Model;

use App\Lib\Response,
App\Lib\Auth;

class WifiModel
{
    private $db;
    private $table = 'users';
    private $response;
    
    public function __construct($db)
    {
        $this->db = $db;
        $this->response = new Response();
    }


    public function register($name,$mac,$id_audit,$namefile="")
    {   
        $sql = "INSERT INTO wifi_data (id_user,id_audit,name,mac,file) VALUES ('1',:id_audit,:name,:mac,:file)";
        $st = $this->db->prepare($sql);

        $st->bindParam(':mac',$mac);
        $st->bindParam(':name',$name);
        $st->bindParam(':file',$namefile);
        $st->bindParam(':id_audit',$id_audit);

        $this->response->result = null;
        if($st->execute()){
            return $this->response->SetResponse(true);
        }else{
            return $this->response->SetResponse(false);
        }


    }

    public function getDataByIdAndUser($id,$id_user)
    {   
        $st = $this->db->prepare("SELECT * FROM wifi_data WHERE id=:id AND id_user=:id_user");

        $st->bindParam(':id',$id);
        $st->bindParam(':id_user',$id_user);
        $this->response->result = null;
        if($st->execute()){
          $result = $st->fetch();
          $this->response->result = $result;
          return $this->response->SetResponse(true);
        }else{
            return $this->response->SetResponse(false);
        }


    }

    public function update($id,$id_user,$name,$file)
    {   
        $st = $this->db->prepare("UPDATE wifi_data set name=:name,file=:file WHERE id=:id AND id_user=:id_user");

        $st->bindParam(':id',$id);
        $st->bindParam(':id_user',$id_user);
        $st->bindParam(':name',$name);
        $st->bindParam(':file',$file);

        $this->response->result = null;
        if($st->execute()){
          return $this->response->SetResponse(true);
        }else{
            return $this->response->SetResponse(false);
        }


    }

    public function activeProcess($id,$id_user)
    {   
        $st = $this->db->prepare("UPDATE wifi_data set process='1' WHERE id=:id AND id_user=:id_user");

        $st->bindParam(':id',$id);
        $st->bindParam(':id_user',$id_user);


        $this->response->result = null;
        if($st->execute()){
          return $this->response->SetResponse(true);
        }else{
            return $this->response->SetResponse(false);
        }


    }

    public function getelementprocess()
    {
        $st = $this->db->prepare("SELECT * FROM wifi_data WHERE process='3' ORDER BY date_create");

        $this->response->result = null;
        if($st->execute()){
          $result = $st->fetch();
          $this->response->result = $result;
          return $this->response->SetResponse(true);
        }else{
            return $this->response->SetResponse(false);
        }
    }


    public function getDataByIdUser($id_user,$id_audit)
    {   
       // var_dump($id_user);
        $st = $this->db->prepare("SELECT * FROM wifi_data WHERE id_user=:id_user AND id_audit=:id_audit");

        $st->bindParam(':id_user',$id_user);
        $st->bindParam(':id_audit',$id_audit);

        $this->response->result = null;
        if($st->execute()){
          $result = $st->fetchAll();
        //  var_dump($result);
          $this->response->result = $result;
          return $this->response->SetResponse(true);
        }else{
            return $this->response->SetResponse(false);
        }


    }




}