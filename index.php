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

// Appel du serveur pour obtenir le hash MD5
$ch = curl_init();
try {
    curl_setopt($ch, CURLOPT_URL, "https://127.0.0.1:8000/");
    curl_setopt($ch, CURLOPT_POST, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
} catch (\Throwable $th) {
    throw $th;
} finally {
    curl_close($ch);
}
// Je décode le résultat envoyé par le serveur
$md5 = json_decode($response);
// J'appel ma fonction en charge de faire la vérification
$deHash = getRealKey($md5->md5);
if (empty($deHash))
    echo displayMessage("Aucune solution trouvée");
// Appel du serveur pour vérifier l'info
$ch = curl_init();
try {
    curl_setopt($ch, CURLOPT_URL, "https://127.0.0.1:8000/get_bitcoin");
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, array(
        "number" => $deHash,
        "md5" => $md5->md5
    ));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $responseCheck = curl_exec($ch);
} catch (\Throwable $th) {
    throw $th;
} finally {
    curl_close($ch);
}
// Je récupère le résultat du serveur
$responseCheck = json_decode($responseCheck);
// J'indique à l'utilisateur le résultat
if ($responseCheck->success)
    echo displayMessage('Bravo, le hash est bon, vous venez de gagner 1 AntoCoin');
else
    echo displayMessage('Aie, le hash n\'est pas bon, veuillez réessayer');
