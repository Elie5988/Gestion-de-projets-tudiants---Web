<?php
session_start();
include_once "connexionDB.php";

$message = ""; // Initialiser le message pour éviter des erreurs non définies

if (isset($_SESSION['student_id']) && is_numeric($_SESSION['student_id'])) {
    $id = intval($_SESSION['student_id']);
    $req = mysqli_query($con, "SELECT * FROM students WHERE id = $id");

    if ($req && mysqli_num_rows($req) > 0) {
        $row = mysqli_fetch_assoc($req);

        if (isset($_POST['button'])) {
            $name = $_POST['name'] ?? '';
            $number = $_POST['number'] ?? '';

            if (!empty($name) && !empty($number)) {
                $updateReq = mysqli_query($con, "UPDATE students SET name = '$name', student_numbers = '$number' WHERE id = $id");

                if ($updateReq) {
                    header("Location: accueille.php");
                    exit();
                } else {
                    $message = "Nom et numéro non modifiés";
                }
            } else {
                $message = "Veuillez remplir tous les champs !";
            }
        }
    } else {
        $message = "Erreur lors de la récupération des données ou aucun étudiant trouvé.";
    }
} else {
    $message = "ID invalide.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Étudiant</title>
    <link rel="stylesheet" href="styl.css">
</head>
<body>
<div class="form modifier">
    <a href="accueille.php" class="back_btn"><img src="images/back.png"> Retour</a>
    <h2>Modifier votre profil</h2>
    <p class="erreur_message">
        <?php if (!empty($message)) echo $message; ?>
    </p>
    <form action="" method="POST">
        <label> Nouveau Nom</label>
        <div>
            <input type="text" name="name" value="<?= isset($row) ? htmlspecialchars($row['name']) : '' ?>" autofocus>
        </div>
        <label> Nouveau Numéro</label>
        <div>
            <input type="text" name="number" value="<?= isset($row) ? htmlspecialchars($row['student_numbers']) : '' ?>">
        </div>
        <a href="modifierPassword.php?id=<?= isset($row) ? $row['id'] : '' ?>" style="text-decoration: none;">Modifier le mot de passe ?</a>
        <input type="submit" value="Modifier" name="button">
    </form>
</div>
</body>
</html>
