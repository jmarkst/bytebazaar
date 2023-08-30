<?php
/**
  * Login.php
  * Naglalaman ng Login controller class.
**/

class _404 extends Controller {
    /**
      * 404 controller class.
      * Controller class para sa 404 error page.
    **/
    
    public function index() {
        /**
          * index()
          * Ipapakita yung default (index) view.
        **/
        $this->view('404');
    }
}