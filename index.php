<?php
include('functions.php');
// Récupération des arguments de l'utilisateur
if (empty($argv[1] || $argv[2] || $argv[3])) {
    echo displayMessage("Vous devez renseigner les 3 paramètres.");
    exit;
}
if (stringToLower($argv[1]) != 'create') {
    echo displayMessage("Vous devez choisir une action valable");
    exit;
}
if (stringToLower($argv[2]) != 'wallet') {
    echo displayMessage("Vous devez choisir un Wallet valide");
    exit;
}
// Création des clés utilisateur et du fichier utilisateur
if (!createJsonFile($argv[3], createKeys()))
    echo displayMessage('Une erreur est survenue lors de la création du fichier JSON');
