<?php
require_once 'config.php';

try
{
    // On se connecte à MySQL
    $pdo = new PDO(DSN, USER, PASS);
    //echo "tout va bien";
}
catch(PDOException $e)
{
    // En cas d'erreur, on affiche un message et on arrête tout
    echo ('Erreur : '.$e->getMessage());
    die();
}
$sql = 'SELECT * FROM friend';
$statement = $pdo->query($sql);
$friends = $statement->fetchAll();


$errors=[];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    if (empty($firstname) || trim($firstname) === '') {
        $errors[] = 'Le prénom est obligatoire';
    } elseif (strlen($firstname < 45)) {
        $errors[] = 'Le prénom ne doit pas dépasser 45 caractères';
    }
    if (empty($lastname) || trim($lastname) === '') {
        $errors[] = 'Le nom est obligatoire';
    } elseif (strlen($lastname < 45)) {
        $errors[] = 'Le nom ne doit pas dépasser 45 caractères';
    }
    if (empty($errors)){
        $sql = "INSERT INTO friend (firstname, lastname) VALUES ('$firstname','$lastname')";
        $statement = $pdo->exec($sql);
    }else{
        foreach ($errors as  $error){
            echo $error . '<br>';
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport"
      content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">
<title>Document</title>
</head>
<body>
<h1>Friends List</h1>
<ul>
<?php if (empty ($friends)) : ?>
<p><strong>NO DATA </strong></p>
<?php else :
foreach ($friends as $friend) : ?>
<li><?= htmlspecialchars($friend['firstname'] ). ' ' . htmlspecialchars($friend['lastname']) ?></li>
<?php endforeach;?>
<?php endif;
?>
</ul>
<h2>Added a Friend</h2>

<form method="post">
    <div>
    <label for="firstname">Firstname :</label>
    <input type="text" id="firstname" name="firstname">
    </div>
    <br>
    <div>
    <label for="lastname">Lastname :</label>
    <input type="text" id="lastname" name="lastname">
    </div>
    <br>
    <button type="submit">Added friend</button>
</form>
</body>
</html>