<?php

require_once 'app/config.php';



if($_SERVER['REQUEST_METHOD'] !== 'POST'){


    die('SORRY CANNOT PERFORM THIS OPERATION');


}



$delete_user = $con->prepare("DELETE FROM users WHERE name = :name");


$delete_user->execute([

    'name' => $_SESSION['name']


]);



?>