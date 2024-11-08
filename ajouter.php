
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter Projet</title>
    <link rel="stylesheet" href="styl.css">
</head>
<body>
<?php
session_start(); // Démarrer la session si ce n'est pas déjà fait

    if (isset($_POST['button'])) {
        extract($_POST);
        if (isset($title) && isset($description) && $statut) {
            include_once "connexionDB.php";
             // Récupérer le student_id de la session
            $student_id = $_SESSION['student_id'];
            $req = mysqli_query($con, "INSERT INTO projet VALUES(NULL, '$title', '$description','$student_id', '$statut')");
            if ($req) {
                header("location: accueille.php");
            } else {
                $message = "Projet non ajouté";
            }
        } else {
            $message = "Veuillez remplir tous les champs !";
        }
    }
?>

<div class="form modifier">
    <a href="accueille.php" class="back_btn"><img src="images/back.png"> Retour</a>
    <h2>Ajouter un projet</h2>
    <p class="erreur_message">
        <?php if (isset($message)) echo $message; ?>
    </p>
    <form action="" method="POST">
        <label>Titre</label>
        <div>
            <input type="text" name="title">
            <p></p>
        </div>
        <label>Description</label>
        <div>
            <input type="text" name="description">
            <p></p>
        </div>
        <label>Statut</label>
       <div>
        <select name="statut">
            <option value="En cours">En cours</option>
            <option value="Commençé">Commençé</option>
            <option value="Terminé">Terminé</option>
        </select>
        <p></p>
       </div>
        <input type="submit" value="Ajouter" name="button">
    </form>
</div>
</body>
</html>

