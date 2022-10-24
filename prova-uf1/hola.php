<!DOCTYPE html>
<html lang="ca">
<head>
    <title>Benvingut</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="style.css" rel="stylesheet">

</head>
<body>
<div class="container noheight" id="container">
    <div class="welcome-container">
        <h1>Benvingut!</h1>
        <div>Hola <?php $_POST['nom'] ?>, les teves darreres connexions són:</div>
        <form action="#">
            <button>Tanca la sessió</button>
        </form>
    </div>
</div>
</body>
</html> 