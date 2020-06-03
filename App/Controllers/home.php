 
<?php

class Home extends Controller {

    protected $user;
    public function __construct() {
        $this->user = $this->model('User');
    }

    public function index($name = '') {
        
        $user = $this->user;
        $user->name = $name;
        $this->view('home/index', ['name' => $user->name]);

        $this->goto('home', 'newsletter');
    }


    public function replacemail($oldmail, $newmail) {
        
        // read the entire file
        $str = file_get_contents('newsletter-list.txt');

        // replace old mail with new mail
        $str=str_replace($oldmail, $newmail, $str);
        
        // rewrite the file
        file_put_contents('newsletter-list.txt', $str);

        // go back to newsletter
        $this->goto('home', 'newsletter');
    }

    public function deletemail($mail) {
        $file = 'newsletter-list.txt';
        $contents = file_get_contents($file);
        $new_contents= "";
        if( strpos($contents, $mail) !== false) { // if file contains ID
            $contents_array = preg_split("/\\r\\n|\\r|\\n/", $contents);
            foreach ($contents_array as &$record) {    // for each line
                if (strpos($record, $mail) !== false) { // if we have found the correct line
                    //pass; // we've found the line to delete - so don't add it to the new contents.
                }else{
                    $new_contents .= $record . "\r"; // not the correct line, so we keep it
                }
            }
            file_put_contents($file, $new_contents); // save the records to the file
            echo json_encode("Sie wurden vom Newsletter abgemeldet");
        }
        else{
            echo json_encode("Failed - EMail ". $mail ." doesn't exist!");
        }

        file_put_contents('newsletter-list.txt',
            preg_replace(
                '~[\r\n]+~',
                "\r\n",
                trim(file_get_contents('newsletter-list.txt'))
            )
        );
        $file = fopen('newsletter-list.txt', 'a+');
        fwrite($file, "\n");

        $this->view('home/newsletter');
        
    }

    
    public function newsletter() {

        // check if form was sent
        if(isset($_POST['Email'])){
            $file = fopen('newsletter-list.txt', 'a+');

            // loop all lines and compare with form inputs
            $write = true;
            while(!feof($file)){

                // split line in parts
                $l = explode(';', fgets($file));

                // check if line is identical
                if($l[0] == $_POST['Email'] && $l[1] == $_POST['Vorname'] && $l[2] == $_POST['Nachname'] && $l[3] == $_POST['Strasse'] && trim($l[4]) == $_POST['Ort']){
                    $write = false; // dont write again
                    echo 'Diese Person wurde bereits erfasst!';
                    break;
                } 
                // check if just mail is different
                elseif($l[1] == $_POST['Vorname'] && $l[2] == $_POST['Nachname'] && $l[3] == $_POST['Strasse'] && trim($l[4]) == $_POST['Ort']) {
                    $write = false; // ask if mail should be replaced
                    echo '
                    Die gleiche Adresse mit einer anderen Email ist bereits vorhanden. Möchten Sie die Mail ersetzen?
                    <br>
                    <a href="/site/testing/MVCTask/public/home/replacemail/'.$l[0].'/'.$_POST['Email'].'">Ja, überschreiben</a>';
                    break;
                }
            }

            // write new line
            if($write == 23){
                fwrite($file, $_POST['Email'].';');
                fwrite($file, $_POST['Vorname'].";");
                fwrite($file, $_POST['Nachname'].";");
                fwrite($file, $_POST['Strasse'].";");
                fwrite($file, $_POST['Ort']."\n");
                echo $_POST['Email'].' wurde registiert';
            }
        }

        // render view
        $this->view('home/newsletter');
    }
    public function newsletter_abmelden(){

        if(isset($_POST['Email2'])){
            $file = fopen('newsletter-list.txt', 'a+');

            // loop all lines and compare with form inputs
            $found = false;
            while(!feof($file)){

                // split line in parts
                $l = explode(';', fgets($file));

                // check if Email exists
                if($l[0] == $_POST['Email2']){
                    $found = true;
                    echo '
                    Wollen Sie sich wirklich abmelden?
                    <br>
                    <a href="/newsletter/bzt.m120.mvc-example-master/public/home/deletemail/'.$l[0].'/'.$_POST['Email2'].'">Ja, abmelden</a>';
                    break;
                }
            }
            if($found == false){
                echo '
                        Diese Mail ist noch nicht angemeldet
                        <br>
                        <a href="/newsletter/bzt.m120.mvc-example-master/public/home/newsletter/">Weiter zur anmeldung?</a>';
                 
            }
        }
        $this->view('home/newsletter_abmelden');
        
    }

    public function abmelden(){
        $this->view('home/newsletter_abmelden');
    }
}

