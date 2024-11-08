<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion Projets</title>
    <link rel="stylesheet" href="accueilleStyleShee.css">
</head>
<body>
    <div class="entete">
        <img src="images/graduated.png" alt="" class="logo">
        <img src="images/menu.png" class="menubtn" alt="">
        <img src="images/cancelbtn.png" class="cancelbtn" alt="">
        <h2 class="title"><span>Liste</span> des projets envoyés</h2>
        <div class="menu">
            <ul>
                <li><a href="accueille.php" id="pageActive">Accueil </a><p></p></li>
                <li><a href="evaluation.php" class="sombre">evaluation </a><p></p></li>
                <li><a href="communaute.php" class="sombre">Communauté</a><p></p></li>
                <li><a href="logout.php" class="sombre">Quitter</a><p></p></li>
            </ul>
        </div>
    </div>
    <div class="bare-recherche">
        <form method="GET" action="">
             <input type="text" name="search" placeholder="Rechercher ..."> 
            <button type="submit"><img src="images/rechercheico.png" alt=""></button>
        </form>
    </div>
    <div class="divPrincipale">
        <div class="image container"><img src="images/imageAcceuil.png" alt=""></div>
        <div class="container">
            <a href="ajouter.php" class="Btn_add"> <img src="images/plus.png"> Ajouter</a>
            
            <table>
                <tr id="items">
                    <th>Titre</th>
                    <th>Description</th>
                    <th>Statut</th>
                    <th>Modifier</th>
                    <th>Supprimer</th>
                </tr>
<?php 
include_once "connexionDB.php";
session_start();

if (!isset($_SESSION['student_id'])) {
    header("Location: index.php");
    exit();
}

$student_id = $_SESSION['student_id'];
$search = isset($_GET['search']) ? mysqli_real_escape_string($con, $_GET['search']) : '';

$query = "SELECT * FROM projet WHERE student_id = '$student_id'";
if ($search) {
    $query .= " AND title LIKE '%$search%'";
}
$req = mysqli_query($con, $query);
if (mysqli_num_rows($req) == 0) {
    echo '<tr><td colspan="5">Il n\'y a pas encore de projet ajouté </td></tr>';
} else {
    while($row = mysqli_fetch_assoc($req)){
        ?>
        <tr class="donne">
            <td class="element_titre"><?= htmlspecialchars($row['title']) ?></td>
            <td class="description_table"><?= htmlspecialchars($row['description']) ?></td>
            <td><?= htmlspecialchars($row['status']) ?></td>
            <td><a href="modifier.php?id=<?= $row['id'] ?>"><img src="images/pen.png"></a></td>
            <td class="supprimeTd" data-project-id="<?= $row['id'] ?>"><a><img src="images/trash.png"></a></td>
        </tr>
        <?php
    }
}
?>
            </table>
        </div>
    </div>
    <p class="description_contenaire" style="display: none;">c'est ici nos description s'affiche</p>
    <div class="paragraphe">
        <p>Chers étudiants, le domaine de l'informatique est l'un des secteurs les plus dynamiques et prometteurs de notre époque. Chaque jour, de nouvelles technologies transforment notre monde.</p>
        <p class="pdeux">Evoluons ensemble </p>
    </div>
    <div class="decision supprimer" id="blockConfirmation" style="display: none; ">
        <img src="images/cancelbtn.png" id="btnAnnuler" height="42px" alt="">
        <div>
            <h2>Suppression de projet</h2>
            <div>
                <img src="images/warning.png" alt=""><p>Voulez-vous supprimer ce projet ?</p>
            </div>
        </div>
        <button id="btnAnnuler">Annuler</button> <button id="confirmButton">Supprimer</button>
    </div>
    <div class="boutton">
        <button class="evaluatioBtn"><a href="evaluation.php">EVALUATION</a></button>
        <button class="communautebtn"><a href="communaute.php" style="text-decoration: none;">COMMUNAUTE</a></button>
    </div>
    <div class="footer"><p>copyrigth &COPY; 2024</p></div>

    <script>
        // Block for confirming deletion
        const deleteButtons = document.querySelectorAll('.supprimeTd');
        const confirmationBlock = document.getElementById('blockConfirmation');
        const confirmButton = confirmationBlock.querySelector('#confirmButton');
        const cancelButton = confirmationBlock.querySelector('#btnAnnuler');

        deleteButtons.forEach((deleteButton) => {
            deleteButton.addEventListener('click', (event) => {
                const projectRow = event.target.closest('tr');
                const projectId = deleteButton.dataset.projectId;

                confirmationBlock.style.display = 'block';

                // Handle confirm button click
                confirmButton.onclick = () => {
                    window.location.href = `supprimer.php?id=${projectId}`;
                };

                // Handle cancel button click
                cancelButton.onclick = () => {
                    confirmationBlock.style.display = 'none';
                };
            });
        });

        function affiche_description() {
            const trElems = document.querySelectorAll('.donne');
            const descriptionElem = document.querySelector('.description_contenaire');

            trElems.forEach(trElem => {
                trElem.addEventListener('mouseover', function() {
                    const secondTd = trElem.querySelectorAll('td')[1];
                    if (secondTd && descriptionElem) {
                        descriptionElem.style.display = 'flex';
                        descriptionElem.innerHTML = "<strong>Description :</strong><br>" + secondTd.innerHTML;
                    }
                });
                trElem.addEventListener('mouseout', function() {
                    if (descriptionElem) {
                        descriptionElem.style.display = 'none';
                    }
                });
            });
        }

        affiche_description();

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

        const evaluatioBtn = document.querySelector(".evaluatioBtn");
        evaluatioBtn.addEventListener('click', () => {
            window.location.href = "evaluation.php";
        });

        const communautebtn = document.querySelector(".communautebtn");
        communautebtn.addEventListener('click', () => {
            window.location.href = "communaute.php";
        });
    </script>
</body>
</html>
