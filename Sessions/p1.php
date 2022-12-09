<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
</head>

<body>
    <h1>Pàgina1</h1>
    <a href="p2.php">p2.php</a>
</body>
</html>
<?php
$cookie_name = "comptador";
$cookie_value = 100;

if(isset($_COOKIE[$cookie_name])) {
    setcookie($cookie_name, $_COOKIE[$cookie_name]+1);
} else {
    setcookie($cookie_name, $cookie_value);
}
?>