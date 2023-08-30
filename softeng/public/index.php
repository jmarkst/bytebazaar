<?php
/**
  * Barangay Senior Citizen System
  * BSCS / 2nd Year 2022-2023
  * ---
  * Main file. End-user.
**/

# Start session
session_start();

require "../app/core/init.php";

# check kung true ang debug (para ipakita yung mga errors o hindi)
DEBUG ? ini_set("display_errors", 1) : ini_set("display_errors", 0);

# init yung App class, tapos
# i-load controller.
$app = new Application();
$app->loadController();