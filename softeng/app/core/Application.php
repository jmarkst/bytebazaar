<?php
/**
* Application.php
* Naglalaman ng Application class,
* ang main class ng buong system.
**/

class Application {
    /**
    * Application class.
    * Main application class ng system.
    **/

    # VARIABLES
    private $controller = "Login";
    private $method = "index";

    private function splitUrl() {
        /**
        * splitUrl():
        * Hahatiin yung 'url=' bilang isang array ng mga strings.
        * Returns:
        * - $url: indexed array of strings na naglalaman ng hinating url=.
        **/

        # Kunin mula sa get variable na 'url=', else 'login' na lang.
        $url = $_GET['url'] ?? 'login';

        # gagawa yung explode() ng array base sa delimiter.
        # tatanggalin ng trim yung dulong / kung meron man.
        $url = explode('/', trim($url, "/"));
        return $url;
    }

    public function loadController() {
        /**
        * loadController():
        * Ilo-load yung controller base sa binigay sa url.
        **/

        $url = $this->splitUrl();

        # filename path ng controller.
        # ucfirst(): capitalize first letter (since lahat ay naka-caps
        #            kapag class per convention).
        $filename = "../app/controllers/".ucfirst($url[0]).".php";

        if (file_exists($filename)) {
            # kung meron, load yung file.
            require $filename;
            # update current controller.
            $this->controller = ucfirst($url[0]);
            # tanggalin yung controller sa url array.
            unset($url[0]);
        } else {
            # i-load yung 404 controller kung wala.
            $filename = "../app/controllers/_404.php";
            require $filename;
            # update controller to 404.
            $this->controller = "_404";
        }

        # instantiate yung controller.
        $controller = new $this->controller;

        # kung merong method (1 index) na ni-specify sa URL,
        # i-set natin yon sa method variable imbes na yung default.
        if (!empty($url[1])) {
            if (method_exists($controller, $url[1])) {
                $this->method = $url[1];
                # tanggalin natin yung method mula sa url array
                unset($url[1]);
            }
        }

        # tawagin na natin yung method.
        call_user_func_array([$controller, $this->method], $url);
    }
}