<!-- <?php
// Inclure la connexion à la base de données
include_once "connexionDB.php";

// Démarrer la session
session_start();

// Vérifier si le formulaire d'inscription a été soumis
if (isset($_POST['inscrire'])) {
    $nom = $_POST['name'];
    $number = $_POST['number'];
    $password = $_POST['password'];
    $passwordConfirm = $_POST['passwordConfirm'];

    // Vérifier que tous les champs sont remplis
    if (empty($nom) || empty($number) || empty($password) || empty($passwordConfirm)) {
        $error_message = "Veuillez remplir tous les champs.";
    } elseif ($password !== $passwordConfirm) {
        $error_message = "Les mots de passe doivent être identiques.";
    } else {
        // Vérifier si le numéro d'étudiant est déjà inscrit
        $stmt = $con->prepare("SELECT * FROM students WHERE student_numbers = ?");
        $stmt->bind_param("s", $number);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 0) {
            // Le numéro n'est pas encore inscrit, procéder à l'insertion
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT); // Hacher le mot de passe
            $stmt_insert = $con->prepare("INSERT INTO students (name, student_numbers, password) VALUES (?, ?, ?)");
            $stmt_insert->bind_param("sss", $nom, $number, $hashedPassword);
            
            if ($stmt_insert->execute()) {
                // Récupérer l'étudiant nouvellement ajouté
                $stmt_student = $con->prepare("SELECT * FROM students WHERE student_numbers = ?");
                $stmt_student->bind_param("s", $number);
                $stmt_student->execute();
                $student = $stmt_student->get_result()->fetch_assoc();

                // Stocker l'ID de l'étudiant dans la session
                $_SESSION['student_id'] = $student['id'];
                
                // Redirection vers la page d'accueil avec l'ID de l'étudiant
                header("Location: accueille.php?id=" . $student['id']);
                exit();
            } else {
                $error_message = "Erreur lors de l'inscription. Veuillez réessayer.";
            }
        } else {
            $error_message = "Ce numéro d'étudiant est déjà inscrit.";
        }

        // Fermer les statements
        $stmt->close();
    }
}

/// Vérifier si le formulaire de connexion a été soumis
if (isset($_POST['connecte'])) {
    $nom = $_POST['name'];
    $number = $_POST['number'];
    $password = $_POST['password'];

    // Vérifier que tous les champs sont remplis
    if (empty($nom) || empty($number) || empty($password)) {
        $error_message = "Veuillez remplir tous les champs.";
    } else {
        // Requête pour vérifier si l'étudiant existe dans la base de données
        $stmt = $con->prepare("SELECT * FROM students WHERE name = ? AND student_numbers = ?");
        $stmt->bind_param("ss", $nom, $number);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $student = $result->fetch_assoc();

            // Vérifier le mot de passe
            if (password_verify($password, $student['passWord'])) {
                // L'étudiant existe, stocker l'ID dans la session
                $_SESSION['student_id'] = $student['id'];

                // Redirection vers la page d'accueil avec l'ID de l'étudiant
                header("Location: accueille.php?id=" . $student['id']);
                exit();
            } else {
                // Mot de passe incorrect
                $error_message = "Mot de passe incorrect !";
            }
        } else {
            // L'étudiant n'existe pas
            $error_message = "Nom ou numéro d'étudiant incorrect !";
        }
        $stmt->close();
    }
}

?> -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign in || Sign up form</title>
    <link rel="stylesheet" href="indexStyle.css">
</head>
<body>

<div class="container" id="container">
    <div class="form-container sign-up-container">
        <form action="" method="POST">
            <h1>Création de compte</h1>
            <span>Veuillez compléter vos informations</span>
            <div class="infield">
                <input type="text" placeholder="Nom" name="name" required />
                <label></label>
            </div>
            <div class="infield">
                <input type="text" placeholder="Numéro" name="number" required />
                <label></label>
            </div>
            <div class="infield">
                <input type="password" placeholder="Mot de passe" name="password" required />
                <label></label>
            </div>
            <div class="infield">
                <input type="password" placeholder="Confirmer le mot de passe" name="passwordConfirm" required />
                <label></label>
            </div>
            <p class="erreur_message"><?php if (isset($error_message)) echo $error_message; ?></p>
            <button type="submit" name="inscrire">S'inscrire</button>
        </form>
    </div>
    <div class="form-container sign-in-container">
        <form action="" method="POST">
            <h1>Connexion</h1>
            <span>Entrez vos informations</span>
            <div class="infield">
                <input type="text" placeholder="Nom" name="name" required />
                <label></label>
            </div>
            <div class="infield">
                <input type="text" placeholder="Numéro" name="number" required />
                <label></label>
            </div>
            <div class="infield">
                <input type="password" placeholder="Mot de passe" name="password" required />
                <label></label>
            </div>
            
            <p class="erreur_message"><?php if (isset($error_message)) echo $error_message; ?></p>
            <button type="submit" name="connecte">Se connecter</button>
        </form>
    </div>
    <div class="overlay-container" id="overlayCon">
        <div class="overlay">
            <div class="overlay-panel overlay-left">
                <h1>Inscription</h1>
                <p>Pour finaliser votre inscription, merci de compléter vos informations.</p>
                <button>Se connecter</button>
            </div>
            <div class="overlay-panel overlay-right">
                <h1>Bienvenue !</h1>
                <p>Connectez-vous et laissez l'aventure commencer !</p>
                <button>S'inscrire</button>
            </div>
        </div>
        <button id="overlayBtn"></button>
    </div>
</div>

<script>
    const container = document.getElementById('container');
    const overlayBtn = document.getElementById('overlayBtn');

    overlayBtn.addEventListener('click', () => {
        container.classList.toggle('right-panel-active');
        overlayBtn.classList.remove('btnScaled');
        window.requestAnimationFrame(() => {
            overlayBtn.classList.add('btnScaled');
        });
    });
</script>

</body>
</html>
