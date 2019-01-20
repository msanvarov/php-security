<?php

require 'app/config.php';


?>


<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
</head>
<body>


<form action="delete.php" method="post">

    <input type="submit" value="Delete">

    <input type="hidden" name="_token" value="<?php echo $_SESSION['_token'] ?>">



</form>




</body>
</html>
