<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Mot de Passe</title>
    <link rel="stylesheet" href="styl.css">
</head>
<body>
<?php
session_start(); // Démarrer la session
include_once "connexionDB.php";

$message = ""; // Initialiser le message

// Vérifie si l'ID est dans la session
if (isset($_SESSION['student_id']) && is_numeric($_SESSION['student_id'])) {
    $id = intval($_SESSION['student_id']);
    $req = mysqli_query($con, "SELECT * FROM students WHERE id = $id");
    
    // Vérifie si la requête a réussi
    if ($req && mysqli_num_rows($req) > 0) {
        $row = mysqli_fetch_assoc($req);

        if (isset($_POST['button'])) {
            // Récupérer les mots de passe
            $old_password = $_POST['old_password'] ?? '';
            $new_password = $_POST['new_password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';

            // Vérification de l'ancien mot de passe
            if (password_verify($old_password, $row['passWord'])) {
                if ($new_password === $confirm_password) {
                    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                    $updateReq = mysqli_query($con, "UPDATE students SET passWord = '$hashed_password' WHERE id = $id");
                    if ($updateReq) {
                        header("Location: accueille.php");
                        exit();
                    } else {
                        $message = "Le mot de passe n'a pas été modifié.";
                    }
                } else {
                    $message = "Le nouveau mot de passe et la confirmation ne correspondent pas.";
                }
            } else {
                $message = "L'ancien mot de passe est incorrect.";
            }
        }
    } else {
        $message = "Erreur lors de la récupération des données ou étudiant non trouvé.";
    }
} else {
    $message = "ID invalide ou non trouvé dans la session.";
}
?>

<div class="form modifier">
<a href="accueille.php" class="back_btn"><img src="images/back.png"> Retour</a>
    <h2>Modifier Mot de Passe</h2>
    <p class="erreur_message">
        <?php if (!empty($message)) echo $message; ?>
    </p>
    <form action="" method="POST">
        <label>Ancien Mot de Passe</label>
        <div>
            <input type="password" name="old_password" required>
        </div>
        <label>Nouveau Mot de Passe</label>
        <div>
            <input type="password" name="new_password" required>
        </div>
        <label>Confirmer Nouveau Mot de Passe</label>
        <div>
            <input type="password" name="confirm_password" required>
        </div>
        <input type="submit" value="Modifier" name="button">
    </form>
</div>
</body>
</html>
