<?php
session_start();
include_once "connexionDB.php";

// Vérifier si l'étudiant est connecté
if (!isset($_SESSION['student_id'])) {
    header("Location: index.php");
    exit();
}

$studentId = $_SESSION['student_id'];

// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $submittedPassword = $_POST['ConfirmPassword'];
    $action = $_POST['action']; // Récupérer l'action du bouton

    // Récupérer le mot de passe haché de la base de données
    $passwordQuery = mysqli_query($con, "SELECT passWord FROM students WHERE id = $studentId");
    $studentData = mysqli_fetch_assoc($passwordQuery);

    // Vérifier le mot de passe
    if (password_verify($submittedPassword, $studentData['passWord'])) {
        // Rediriger en fonction de l'action
        switch ($action) {
            case 'modifier':
                header("Location: mdpModifier.php");
                break;
            case 'desactiver':
                header("Location: DesactiverCompt.php");
                break;
            case 'suprimer':
                header("Location: SuprimerEtudiante.php");
                break;
            case 'deconnecter':
                header("Location: logout.php");
                break;
        }
        exit();
    } else {
        // Afficher un message d'erreur si le mot de passe est incorrect
        $error = "Le mot de passe est incorrect.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Communauté</title>
    <link rel="stylesheet" href="communautee.css">
</head>
<body>
    <div class="identite">
        <div class="profil">
            <div>
                <img src="images/graduated.png" alt="">
                <p>
                    <?php 
                    $studentQuery = mysqli_query($con, "SELECT name FROM students WHERE id = $studentId");
                    $student = mysqli_fetch_assoc($studentQuery);
                    echo htmlspecialchars($student['name']);
                    ?>
                </p>
                <hr class="profilTrait">
            </div>
            <div class="element"><span>Numéro :</span><p>
                <?php 
                $studentNumberQuery = mysqli_query($con, "SELECT student_numbers FROM students WHERE id = $studentId");
                $studentNumber = mysqli_fetch_assoc($studentNumberQuery);
                echo htmlspecialchars($studentNumber['student_numbers']);
                ?>
            </p></div>
            <div class="element"><span>Identifiant :</span><p>00<?php echo htmlspecialchars($studentId); ?></p></div>
        </div>
        
        <div class="projetInfo">
            <h2 id="statustitre">Information de travail</h2>
            <hr>

            <div id="information_de_projet">
                <div class="element"><span>Nombre de vos projets envoyés :</span><p>
                    <?php 
                    $projectsSentQuery = mysqli_query($con, "SELECT COUNT(*) as count FROM projet WHERE student_id = $studentId");
                    $projectsSent = mysqli_fetch_assoc($projectsSentQuery);
                    echo htmlspecialchars($projectsSent['count']);
                    ?>
                </p></div>
                <div class="element"><span>Nombre de vos projets évalués :</span><p>
                    <?php 
                    $projectsEvaluatedQuery = mysqli_query($con, "SELECT COUNT(*) as count FROM evaluation WHERE project_id IN (SELECT id FROM projet WHERE student_id = $studentId)");
                    $projectsEvaluated = mysqli_fetch_assoc($projectsEvaluatedQuery);
                    echo htmlspecialchars($projectsEvaluated['count']);
                    ?>
                </p></div>
                <div id="infosatuts">
                    <span>Statut</span>
                    <div>
                        <div><span>Commencé</span><p>
                            <?php 
                            $projectsStartedQuery = mysqli_query($con, "SELECT COUNT(*) as count FROM projet WHERE student_id = $studentId AND status = 'Commençé'");
                            $projectsStarted = mysqli_fetch_assoc($projectsStartedQuery);
                            echo htmlspecialchars($projectsStarted['count']);
                            ?>
                        </p></div>
                        <div><span>En cours</span><p>
                            <?php 
                            $projectsInProgressQuery = mysqli_query($con, "SELECT COUNT(*) as count FROM projet WHERE student_id = $studentId AND status = 'En cours'");
                            $projectsInProgress = mysqli_fetch_assoc($projectsInProgressQuery);
                            echo htmlspecialchars($projectsInProgress['count']);
                            ?>
                        </p></div>
                        <div><span>Terminé</span><p>
                            <?php 
                            $projectsFinishedQuery = mysqli_query($con, "SELECT COUNT(*) as count FROM projet WHERE student_id = $studentId AND status = 'Terminé'");
                            $projectsFinished = mysqli_fetch_assoc($projectsFinishedQuery);
                            echo htmlspecialchars($projectsFinished['count']);
                            ?>
                        </p></div>
                    </div>
                </div>
            </div>
        </div>
        <div id="lesInformation"> 
                <div class="content">
                    <div>
                        <p id="confidentialite">Confidentialité</p> <img src="" alt="">
                    </div>
                    <div style="position: relative;" id="compteConnect">
                        <div id="compte">
                            <p id="desactivation" >Désactiver</p>
                            <p id="supressionDuCompte">Supprimer</p>
                        </div>
                        <img src="images/previousTop.png" id="compteImg" style="margin-right: 9px;" alt=""><p style="position: absolute;">Compte</p>
                    </div>
                    <div>
                        <p id="SeDeconnecte">Se déconnecter</p> <img src="" alt="">
                    </div>
                </div>
        </div>
        <button class="profilbtn" id="plusDinformation">Plus d'information <img src="images/previous.png" alt=""></button>
    </div>
    
    <div class="entete">
        <img src="images/menu.png" class="menubtn" alt="">
        <img src="images/cancelbtn.png" class="cancelbtn" alt="">
        <h2 class="title"><span>Projet</span> de tous les étudiants</h2>
        <div class="menu">
            <ul>
                <li><a href="accueille.php">Accueil</a></li>
                <li><a href="evaluation.php">Évaluation</a></li>
                <li><a href="communaute.php" class="sombre" id="pageActive">Communauté</a></li>
                <li><a href="logout.php" class="sombre">Quitter</a></li>
            </ul>
        </div>
    </div>

    <div class="container">
        <div class="bare-recherche">
            <form method="GET" action="">
                <input type="text" name="search" placeholder="Rechercher ..."> 
                <button type="submit"><img src="images/rechercheico.png" alt=""></button>
            </form>
        </div>
        
        <div id="tableprincipale">
            <table>
                <tr id="items">
                    <th>Titre</th>
                    <th>Description</th>
                    <th>Statut</th>
                    <th>Nom de l'étudiant</th>
                </tr>
                <?php 
                $search = isset($_GET['search']) ? mysqli_real_escape_string($con, $_GET['search']) : '';
                $searchCondition = $search ? "WHERE projet.title LIKE '%$search%' OR projet.description LIKE '%$search%'" : '';
                
                $req = mysqli_query($con, "
                    SELECT projet.title, projet.description, projet.status, students.name
                    FROM projet AS projet
                    JOIN students ON projet.student_id = students.id
                    $searchCondition
                ");

                if (mysqli_num_rows($req) == 0) {
                    echo "<tr><td colspan='4'>Il n'y a pas encore de projet ajouté !</td></tr>";
                } else {
                    while ($row = mysqli_fetch_assoc($req)) {
                        ?>
                        <tr class="tr">
                            <td><?= htmlspecialchars($row['title']) ?></td>
                            <td><?= htmlspecialchars($row['description']) ?></td>
                            <td><?= htmlspecialchars($row['status']) ?></td>
                            <td><?= htmlspecialchars($row['name']) ?></td>
                        </tr>
                        <?php
                    }
                }
                ?>
            </table>
        </div>
    </div>

    <a href="accueille.php" class="back_btn"><img src="images/back.png"> Retour</a>

    <div id="demandeConfirmationBlock">
        <div id="demandeConfirmationContent">
            <h2>Verification</h2>
            <img src="images/cancelbtn.png" alt="" annuler>
            <form action="" method="POST">
                <p id="paragraphedeConfirmation">Pour continuer, merci de remplir le champ</p>
                <input type="password" placeholder="Entrer votre mot de passe" name="ConfirmPassword" required>
                <div>
                    <button type="button" class="annuler" onclick="document.getElementById('demandeConfirmationBlock').style.display='none';">Annuler</button>
                    <button type="submit" name="action" id="Confirmationbtn">Ok continuer</button>
                </div>
                <?php if (isset($error)) { echo "<p style='color:red;'>$error</p>"; } ?>
            </form>
        </div>
    </div>

    <script>
        const menubtn = document.querySelector(".menubtn");
        const menulist = document.querySelector(".menu");
        const cancelbtn = document.querySelector(".cancelbtn");
        const entete = document.querySelector(".entete");
        const body = document.querySelector('body');

        menubtn.addEventListener('click', () => {
            menulist.classList.toggle('mobile');
            menubtn.style.display = 'none';
            cancelbtn.style.display = 'block';
            entete.classList.toggle('entete-active');
        });
        cancelbtn.addEventListener('click', () => {
            menulist.classList.toggle('mobile');
            menubtn.style.display = 'block';
            cancelbtn.style.display = 'none';
            entete.classList.toggle('entete-active');
            body.style.left = '0%';
        });



        const info = document.querySelector('.identite .profilbtn')
       const lesInformation = document.getElementById('lesInformation');
       info.addEventListener('mouseover', () => {
        
        lesInformation.style.display='flex';
        lesInformation.style.zIndex='20';
        info.style.zIndex='19';
   
       });
       info.addEventListener('mouseout', () => {
        document.getElementById('imagdeInfo').src = "images/previous.png";
        lesInformation.style.display='none';
   
        });

        lesInformation.addEventListener('mouseover', () => {
            lesInformation.style.display='flex';
   
        });
        lesInformation.addEventListener('mouseout', () => {
            lesInformation.style.display='none';
   
        });
       const compteConnecte = document.getElementById('compteConnecte');
       const compte = document.getElementById('compte');

       compteConnect.addEventListener('mouseover', () => {
            compte.style.display='flex';
   
        });
        compteConnect.addEventListener('mouseout', () => {
            compte.style.display='none';
   
        });

        // affichage des arriere plan 
         const confidentialite = document.getElementById('confidentialite');
         const annuler = document.querySelector('.annuler');
         const paragrapheDeConfirmation = document.getElementById('paragraphedeConfirmation');
         const desactivation = document.getElementById('desactivation');
         const SeDeconnecte = document.getElementById('SeDeconnecte');
         const supression = document.getElementById('supressionDuCompte');
         const Confirmationbtn= document.getElementById('Confirmationbtn');
         confidentialite.addEventListener('click', () => {
            paragrapheDeConfirmation.innerText="Pour accéder à votre compte personnel ou les modifier merci remplir le champ";
            document.getElementById('demandeConfirmationBlock').style.display='flex';
            annuler.addEventListener('click',()=>{
                document.getElementById('demandeConfirmationBlock').style.display='none';
            });
            Confirmationbtn.value="modifier";
            Confirmationbtn.style.backgroundColor="red";
            Confirmationbtn.innerText="Continuer";

        });

        SeDeconnecte.addEventListener('click', () => {
            paragrapheDeConfirmation.innerText="Si vous voulez quitter votre compte, merci de bien remplir le champ";
            document.getElementById('demandeConfirmationBlock').style.display='flex';
            annuler.addEventListener('click',()=>{
                document.getElementById('demandeConfirmationBlock').style.display='none';
            });
            Confirmationbtn.value="déconnecter";
            Confirmationbtn.style.backgroundColor="red";
            Confirmationbtn.innerText="se déconnecter";
        });
        supression.addEventListener('click', () => {
            paragrapheDeConfirmation.innerHTML="La suppression du compte risquera vos donneés d'être supprimées <br> si vous voulez supprimer Merci de remplir le champ";
            document.getElementById('demandeConfirmationBlock').style.display='flex';
            annuler.addEventListener('click',()=>{
                document.getElementById('demandeConfirmationBlock').style.display='none';
            });
            Confirmationbtn.value="supprimer";
            Confirmationbtn.style.backgroundColor="red";
            Confirmationbtn.innerText="Supprimer";
        });
        desactivation.addEventListener('click', () => {
            paragrapheDeConfirmation.innerHTML="La desactivation du compte risquera vos donneés d'être endommagées <br>si vous vouler supprimer Merci de remplir le champ";
            document.getElementById('demandeConfirmationBlock').style.display='flex';
            annuler.addEventListener('click',()=>{
                document.getElementById('demandeConfirmationBlock').style.display='none';
            });
            Confirmationbtn.value="désactiver";
            Confirmationbtn.style.backgroundColor="red";
            Confirmationbtn.innerText="Désactiver";
        });


       </script>
    </script>
</body>
</html>
