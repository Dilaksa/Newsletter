<?php

class user extends Controller {

    
    
    public function show($name = '') {

        $this->view('user/show');
    }
}
