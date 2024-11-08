
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Projet</title>
    <link rel="stylesheet" href="styl.css">
</head>
<body>
<?php
    include_once "connexionDB.php";
    $id = $_GET['id'];
    $req = mysqli_query($con, "SELECT * FROM projet WHERE id = $id");
    $row = mysqli_fetch_assoc($req);

    if (isset($_POST['button'])) {
        extract($_POST);
        if (isset($title) && isset($description) && isset($status)) {
            $req = mysqli_query($con, "UPDATE projet SET title = '$title', description = '$description', status = '$status' WHERE id = $id");
            if ($req) {
                header("location: accueille.php");
            } else {
                $message = "Projet non modifié";
            }
        } else {
            $message = "Veuillez remplir tous les champs !";
        }
    }
?>

<div class="form modifier">
    <a href="accueille.php" class="back_btn"><img src="images/back.png"> Retour</a>
    <h2>Modifier le projet</h2>
    <p class="erreur_message">
        <?php if (isset($message)) echo $message; ?>
    </p>
    <form action="" method="POST">
        <label>Titre</label>
        <div>
            <input type="text" name="title" value="<?= $row['title'] ?>">
            <p></p>
        </div>
        <label>Description</label>
        <div>
            <input type="text" name="description" value="<?= $row['description'] ?>">
            <p></p>
        </div>
        <label>Statut</label>
        <div>
            <select name="status" id="">
                <option value="<?= $row['status'] ?>"><?= $row['status'] ?></option>
                <option value="En cours">En cours</option>
                <option value="Commencer">Commencer</option>
                <option value="Termineé">Termineé</option>
    
            </select>
            <p></p>
        </div>
        <input type="submit" value="Modifier" name="button">
    </form>
</div>
</body>
</html>




