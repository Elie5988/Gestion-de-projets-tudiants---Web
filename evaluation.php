<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Evaluation</title>
    <link rel="stylesheet" href="evaluationStyl.css">
</head>
<body>
    <div class="entete">
        <img src="images/graduated.png" alt="" class="logo">
        <img src="images/menu.png" class="menubtn" alt="">
        <img src="images/cancelbtn.png"  class="cancelbtn" alt="">
        <h2 class="title"><span>Liste</span> des projet evalueé</h2>
        <div class="menu">
            <ul>
                <li><a href="accueille.php">Acceuille </a><p></p></li>
                <li><a href="evaluation.php" id="pageActive">evaluation </a><p></p></li>
                <li><a href="communaute.php" class="sombre">Communauté</a><p></p></li>
                <li><a href="logout.php" class="sombre">Quitter</a><p></p></li>
            </ul>
        </div>
    </div>
    <div class="evaluation_container">
    <h2>EVALUATION</h2>

            <?php 
                // Inclure la page de connexion à la base de données
                include_once "connexionDB.php";
                session_start();

                // Vérifier si l'étudiant est connecté
                if (!isset($_SESSION['student_id'])) {
                    header("Location: index.php");
                    exit();
                }

                // Récupérer l'ID de l'étudiant connecté
                $student_id = $_SESSION['student_id'];
                // Requête pour récupérer les évaluations pour les projets de cet étudiant
                $req = mysqli_query($con, 
                    "SELECT p.title, e.feedback, e.grade, e.id
                    FROM evaluation e
                    JOIN projet p ON p.id = e.project_id
                    WHERE p.student_id = '$student_id'"
                );

                // Vérifier s'il y a des évaluations disponibles
                if(mysqli_num_rows($req) == 0){
                    echo "<tr><td colspan='4'>Pas encore d'évaluation pour vos projets.</td></tr>";
                } else {
                    // Boucler sur les résultats et les afficher dans le tableau
                    while($row = mysqli_fetch_assoc($req)){
                        ?>
                        <div id="titre"><strong>titre :</strong><p><?= $row['title'] ?></p></div>
                        <div id="feedback"><strong>Feedback :</strong> <p><?= $row['feedback'] ?>.</p></div>
                        <div id="grade"><strong>Note :</strong><p></strong><p><?= $row['grade'] ?></p></div>
                        <?php

                    }
                }
            ?> 
            
    </div>
    <a href="accueille.php" class="back_btn"><img src="images/back.png"> Retour</a>


    
    <script>
        
        const menubtn = document.querySelector(".menubtn");
        const menulist = document.querySelector(".menu");
        const cancelbtn = document.querySelector(".cancelbtn");
        const entete = document.querySelector(".entete");
        const body =document.querySelector('body');
        
    
        menubtn.addEventListener('click', () => {
            menulist.classList.toggle('mobile');
            menubtn.style.display='none';
            cancelbtn.style.display='block';
            entete.classList.toggle('entete-active');
    
        });
        cancelbtn.addEventListener('click', () => {
            menulist.classList.toggle('mobile');
            menubtn.style.display='block';
            cancelbtn.style.display='none';
            entete.classList.toggle('entete-active');
            body.style.left='0%';
        });

        </script>
    
</body>
</html>
