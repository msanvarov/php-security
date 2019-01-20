<?php

phpinfo();

//error_reporting(0);
// ini_set('display_errors', 'Off');

$con = new PDO('mysql:host=127.0.0.1;dbname=sql_injectdion', 'root', ''); // will produce an error
