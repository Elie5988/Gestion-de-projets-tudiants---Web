<?php
session_start(); // Démarre la session

// Détruire toutes les variables de session
session_unset();

// Détruire la session
session_destroy();

// Rediriger vers la page de login
header("Location: index.php");
exit;
?>