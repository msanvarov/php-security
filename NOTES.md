## SQL-Injection
### To proceed please navigate to the sql folder and import the sql file to phpmyadmin before accessing index.php

#### Problem: No Escape on Forms
* Let's assume that you create a simple form to take in user data.

``` PHP
<?php
$con = new PDO('mysql:host=localhost;dbname=sql_injection', 'root', ''); // connecting to db
//OR
$con = new mysqli("localhost", "root", "", "sql_injection");
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
        Email
        <input type="text" name="email">
    </label>
    <input type="submit" >
</form>
</body>
</html>
```
* Then you decide to process the form like someone normally would.

``` PHP
<?php
$con = new PDO('mysql:host=localhost;dbname=sql_injection', 'root', ''); // connecting to db

// OR
$con = new mysqli("localhost", "root", "", "sql_injection");

// processing form input
if (isset($_POST['email']))
{
    $email = $_POST['email'];
    $query = $con->query("SELECT * FROM users WHERE email = '{$email}'");
    // for PDO
    if ($query->rowCount())
    {
        echo "found an email address!";    
    }

    // for MYSQL
    if ($query->num_rows)
    {
        echo "found an email address!";    
    }
}

?>
// ... hidden body of the form
```
* The code above can seem normal but it is in fact extremely flawed

#### If I was a hacker and I would write queries to test whether the developer made sure to escape the input provided by users.

* Examples of queries I can execute in the email input bar.

```
'; DROP TABLE posts; --'
/* translates to the following query:
"SELECT * FROM users WHERE email = ''; DROP TABLE posts; --'");
*/
```

#### Solution:

* Use prepare statements or escape each string input

``` PHP
<?php
// for PDO
$query = $con->prepare("SELECT * FROM users WHERE email = :email ");
$query->execute(array('email'=>$email));

// for MYSQL
$email = $mysqli->real_escape_string($email);
 $con->query("SELECT * FROM users WHERE email = '($email)'");
 ?>
```
#### Problem: Errors
