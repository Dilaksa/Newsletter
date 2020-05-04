<?php

class test extends Controller {
    
    public function sendNews(){
    echo "Willkommen in der Liste" .$GET['mail'];
    $myfile = "liste.txt";
    if (file_exists($myfile)){
        file_put_contents($myfile, $_GET['mail']);
    } else {
        $myfile = fopen("liste.txt", "w") or die("Unable to open file!");
        fwrite($myfile, $_GET['mail']);
        }
    }
}