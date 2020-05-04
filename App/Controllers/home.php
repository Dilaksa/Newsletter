<?php

class Home extends Controller {

    
    public function index($name = '') {
        
       $user =  $this->model('User');
       $user->name = $name;

        $this->view('home/index', ['user' => $user]);

        $this->goto('home', 'newsletter');
    }

    public function newsletter(){

        if(isset($_POST['mail'])){  //check if post  is present

            $file = fopen('list.txt', 'a+');

            $write = true;
            while(!feof($file)){
                if(trim(fgets($file)) == $_POST['mail']){
                    $write = false;
                }
            }

            if($write == true){
                fwrite($file, $_POST['mail']. "\n");
            } else {
                echo $_POST['mail'].' wurde bereits erfasst!';
            }
        }
        
        $this->view('home/newsletter');
    }
}
?>
