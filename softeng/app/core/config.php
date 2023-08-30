<?php
/**
* config.php
* Naglalaman ng mga configuration para sa system.
* Ie. system-wide globals, etc.
**/

# main root para sa mga files.
# baguhin base sa location ng files mula sa htdocs.
#define('ROOT', 'http://localhost/barangay/public');
#define('APP', 'http://localhost/barangay/app');

define('ROOT', 'http://localhost:8003/public');
define('APP', 'http://localhost:8003/app');

# database config
define('DBHOST', 'localhost');
define('DBUSER', 'root');
define('DBPASS', '');
define('DBNAME', 'barangay');

# pangalan ng system
define("APPNAME", 'Barangay Citizen System');
# kung true, ipakita yung error.
define("DEBUG", true);