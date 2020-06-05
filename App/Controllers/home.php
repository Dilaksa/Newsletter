        
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
                $str = file_get_contents('newsletterList.txt');

                // replace old mail with new mail
                $str=str_replace($oldmail, $newmail, $str);
                
                // rewrite the file
                file_put_contents('newsletterList.txt', $str);

                // go back to newsletter
                $this->goto('home', 'newsletter');
            }

            

            
            public function newsletter() {

                // check if form was sent
                if(isset($_POST['Email'])){
                    $file = fopen('newsletterList.txt', 'a+');

                    // loop all lines and compare with form inputs
                    $write = true;
                    while(!feof($file)){
                        $line = fgets($file);
                        if(strlen($line)===0){
                            break;
                        }
                        // split line in parts
                        $l = explode(';', $line);

                        // check if line is identical
                        if($l[0] == $_POST['Email'] && $l[1] == $_POST['Vorname'] && $l[2] == $_POST['Nachname'] && $l[3] == $_POST['Strasse'] && trim($l[4]) == $_POST['Ort']){
                            $write = false; // dont write again
                            echo 'Diese Person wurde bereits erfasst!';
                            break;
                        } 
                        // check if just mail is different
                        elseif($l[1] == $_POST['Vorname'] && $l[2] == $_POST['Nachname'] && $l[3] == $_POST['Strasse'] && trim($l[4]) == $_POST['Ort']) {
                            $write = false; // ask if mail should be replaced

                            echo 'Die gleiche Adresse mit einer anderen Email ist bereits vorhanden. Möchten Sie die Mail ersetzen?';
                            echo '<a href="/Newsletter/public/home/replacemail/'.$l[0].'/'.$_POST['Email'].'">Ja, überschreiben</a>';
                        
                            break;
                        }
                            
                    }
                    
                    // write new line
                    if($write ){ 
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

            public function deletemail($mail) {
                $file = 'newsletterList.txt';
                $contents = file_get_contents($file);
                $lines = file($file); // add some error-checking here to make sure it worked
                $filtered = '';
                foreach($lines as $line) {
                    if(strpos($line, $mail) !== 0) {
                    $filtered .= $line;
                    }
                }
                file_put_contents($file, $filtered);
                // go back to newsletter
                $this->goto('home', 'newsletter');
            }
            
            public function newsletter_abmelden(){
                // check if form was sent
                if(isset($_POST['Email'])){
                    $found = false;
                    $file = fopen('newsletterList.txt', 'a+');

                    // loop all lines and compare with form inputs
                    $write = true;
                    while(!feof($file)){

                        // split line in parts
                        $l = explode(';', fgets($file));    

                        // check if Email1 exist
                        if($l[0] == $_POST['Email']){
                            $found = true; // dont write again
                            echo 'Wollen Sie wirklich abmelden?';

                            echo '<a href="/Newsletter/public/home/deletemail/'.$_POST['Email'].'">Ja, abmelden</a>'; // 

                            break;

                        }
                    }
                if ($found == false){   // email not exist
                    echo 'Diese Mail ist noch nicht angemeldet'; 
                
                // render view  
                $this->view('home/newsletter');
                //echo '<a href="/Newsletter/public/home/newsletter/"> Möchten Sie sich anmelden? </a>'; // link to login 

                }
                }
                else{
                    
                $this->view('home/newsletter_abmelden');
                }

            }

        }