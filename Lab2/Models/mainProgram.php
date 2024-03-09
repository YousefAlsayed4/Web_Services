<?php

use Illuminate\Database\Capsule\Manager as Capsule;

require_once("./DbHandler.php");

class mainProgram implements DbHandler{
    private $_capsule;

    public function __construct(){
        $this->_capsule = new Capsule;
    }       
        public function connect(){
            try{
            $this->_capsule->addConnection([
                "driver" => __DRIVER_DB__,
                "host" =>__HOST_DB__,
                "database" => __NAME_DB__,
                "username" => __USERNAME_DB__,
                "password" => __PASS_DB__
             ]);
             $this->_capsule->setAsGlobal();
             $this->_capsule->bootEloquent();
             return true;
        } catch(\Exception $e){
            echo "ERROR :" .$e->getMessage();
            return false;
        }
        } 
        public function get_data($fields = array(),  $start = 0){
            $items=items::skip($start)->take(5)->get();
            if(empty($fields)){
               
                foreach($items as $item){
                    foreach($item as $row){
                        echo $item->id ."<br>";
            }
        }
    }
    else{
        return $items;

    }
   
    
}
        public function disconnect(){
           try{
            Capsule::disconnect();
            return true;
           }
           catch(\Exception $e){
            echo "Error:" .$e->getMessage();
            return false;
        }   
    }
        public function get_record_by_id($id,$primary_key){
            $item=items::where($primary_key,"=",$id)->get();
            if(count($item) > 0){
                return $item[0];
           }
        }
        public function get_data_by_column($name_column,$value){
            $item=items::where($name_column,"like","%$value%")->get();
            if(count($item) > 0){
                return $item;
           }
        }
        

}