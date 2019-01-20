<?php

    $expires = new DateTime('+1 day');

     setcookie('username', 'msanvarov', $expires->getTimestamp(), '/', null, null, true );

    echo $_COOKIE['username'];





?>
