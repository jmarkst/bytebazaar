<?php
/**
* functions.php
* Naglalaman ng mga project-wide functions.
**/

function show($data) {
    /**
    * show($data):
    * Utility function para maipakita yung data sa pagitan ng mga pre tag.
    * Intended lang para sa debugging.
    * Parameters:
    * - $data: yung data na ipi-print.
    **/
    echo "<pre>";
    print_r($data);
    echo "</pre>";
}

function showError($msg) {
    /**
    * showError($msg):
    * Utility function para sa pagpapakita ng error.
    * Intended lang para sa debugging.
    * Parameters:
    * - $msg: yung mensahe.
    **/
    echo "<b class=\"error\">ERROR:</b> ".$msg;
}

function redirect($path = "") {
    /**
    * redirect($path):
    * - Redirects to $path.
    * Parameters:
    * - $path: yung path.
    **/
    header("location: ".ROOT."/".$path);
    die;
}

function isLogin() {
    /**
     * isLogin():
     * - Check kung login ba ang user.
     * Returns:
     * - true kung naka-login.
    **/
    if (isset($_SESSION['username'])) {
        return true;
    }
    return false;
}