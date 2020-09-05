<?php

namespace model;
use config\database;

class user 
{

    private $pdo;
    
    function __construct()
    {
        $this->pdo = database::pdoConnection();
        $this->pdo->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    public function test($payload) 
    {

        $stmt = $this->pdo->query("select * from `users` ");
        $row = $stmt->fetch();

        http_response_code(200);
        return $row;
    }

    public function is($type, $data)
    {
        
        return ["code"=>"!!!!"];
        
        try {


            $stmt = $this->pdo->prepare("SELECT idx FROM `users` WHERE :type = :data ");
            $stmt->bindParam(":type", $type, \PDO::PARAM_STR);
            $stmt->bindParam(":data", $$data, \PDO::PARAM_STR);
            if ($stmt->execute()) {
                $row = $stmt->fetch();
                return ["code"=>1, "msg"=>"success!", "data"=>$row];
            }


        } catch (\PDOException $e) {
            return [ "code"=>2, "msg"=>"is failed...", "data"=> $e ];
        }

    }

    public function create($payload)
    {


        try {
            
            $this->pdo->beginTransaction();

            if ($this->pdo->inTransaction())
            {

                $is = $this->is("id", $payload['id']);
                return ["is"=>$is];
                if ($is['data'] != null) {
                    return ["code"=>2, "msg"=>"중복 아이디입니다.", "data"=>""];
                }

                return ["code"=>1, "msg"=>"success!", "data"=>""];
                $stmt = $this->pdo->prepare("");
            }

        } catch(\PDOException $e) {

        }

    }

}