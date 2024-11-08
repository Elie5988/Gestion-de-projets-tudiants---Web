<?php
session_start();
include_once "connexionDB.php";

// Vérifier si l'étudiant est connecté
if (!isset($_SESSION['student_id'])) {
    header("Location: index.php");
    exit();
}

$studentId = $_SESSION['student_id'];

// Vérifier si la requête est une désactivation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Mettre à jour le statut de l'étudiant à 'désactivé'
    $updateQuery = "UPDATE students SET status = 'désactivé' WHERE id = ?";
    
    if ($stmt = mysqli_prepare($con, $updateQuery)) {
        mysqli_stmt_bind_param($stmt, "i", $studentId);
        if (mysqli_stmt_execute($stmt)) {
            // Détruire la session et rediriger
            session_destroy();
            header("Location: index.php?message=Votre compte a été désactivé avec succès.");
            exit();
        } else {
            echo "Erreur lors de la désactivation de votre compte.";
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "Erreur lors de la préparation de la requête.";
    }
} else {
    // Redirection si la méthode n'est pas POST
    header("Location: communaute.php");
    exit();
}
?>
