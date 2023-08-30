<?php
/**
* init.php
* Initialization ng mga core files.
* Para mabilis ma-import.
**/

# kung walang mahanap na class, i-load yung model file mula /models
spl_autoload_register(function ($class) {
    require "../app/models/".ucfirst($class).".php";
});

# requirements
require "config.php";
require "functions.php";
require "Database.php";
require "Model.php";
require "Controller.php";
require "Application.php";