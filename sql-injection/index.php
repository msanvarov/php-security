<?php
$con = new PDO('mysql:host=localhost;dbname=sql_injection', 'root', '');
if (isset($_POST['email'])) {
    $email = $_POST['email'];
    $query = $query = $con->prepare("SELECT * FROM users WHERE email = :email ");
    $query->execute(array('email' => $email));
    if ($query->rowCount()) {
        echo "found an email address!";
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
</head>
<body>

<form action="" method="post" autocomplete="off">
    <label for="name">
        email
        <input type="text" name="email">
    </label>
    <input type="submit" >

</form>

</body>
</html>
