<?php
/**
* Controller.php
* Naglalaman ng Controller class.
**/

class Controller {
    /**
    * Controller class.
    * Main definition class para sa mga
    * controllers (per MVC framework).
    **/

    public function view($name, $data = []) {
        /**
        * view($name):
        * Ilo-load yung view na $name na gagamitin
        * ng controller.
        * Parameters:
        * - $name: yung pangalan ng view na gagamitin.
        * - $data: array na gagamitin ng view.
        **/

        # import yung laman ng data kung may laman.
        if (!empty($data) && is_array($data)) {
            extract($data);
        }

        # filename parh ng view.
        $filename = "../app/views/".$name."View.php";

        # if file exist, i-load yung filename.
        if (file_exists($filename)) {
            require $filename;
        } else {
            # i-load yung 404 view kung wala.
            $filename = "../app/views/404View.php";
            require $filename;
        }
    }

}