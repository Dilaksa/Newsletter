<?php

class Controller{

    public function model($model){
        require_once('../app/models/' . $model . '.php');
        return new $model();
    }

    public function view($view, $data = []){
        require_once('../app/view/' . $view . '.php');
    }
        
    public function goto($controller, $method, $args = []){
        $base = '/MVC_Test/Public';
        $location = 'http://' .$_SERVER['HTTP_HOST'] . $base . "/" .$controller . "/" .$method . "/" . implode("/",$args);
        header("Location: " . $location);
        exit;
    }
}

?>
