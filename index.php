<?php
include('functions.php');
// Récupération des arguments de l'utilisateur
if (empty($argv[1])) {
    echo displayMessage("Vous devez renseigner au minimum 1 paramètre.");
    exit;
}

if (stringToLower($argv[1]) == 'create') {
    if (empty($argv[2] || $argv[3])) {
        echo displayMessage("Vous devez renseigner les 2 autres paramètres");
        die;
    }
    if (stringToLower($argv[2]) != 'wallet') {
        echo displayMessage("Vous devez choisir un paramètre de Wallet valide");
        exit;
    }
}

if (
    stringToLower($argv[1] != 'create')
    && (stringToLower($argv[1] != 'mine')
        && (stringToLower($argv[1] != 'list')))
) {
    echo displayMessage("Vous devez choisir une action valables");
    exit;
}

if (stringToLower($argv[1] == 'create')) {
    // Création des clés utilisateur et du fichier utilisateur
    if (!createJsonFile($argv[3], createKeys())) {
        echo displayMessage('Une erreur est survenue lors de la création du fichier JSON');
        die;
    } else {
        echo displayMessage("Votre token ainsi que votre wallet viennent d'être crées");
        die;
    }
}

if (stringToLower($argv[1] == 'mine')) {

    // Je stock les informations de clé
    $keys = getKey($argv[2]);
    $alpha = $keys->alpha_key;
    $beta = $keys->beta_key;
    for ($i = 0; $i < 30; $i++) {
        // Appel du serveur pour obtenir le hash MD5
        $ch = curl_init();
        try {
            curl_setopt($ch, CURLOPT_URL, "https://127.0.0.1:8000/");
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, array(
                "cleAlpha" => $alpha
            ));
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
                "md5" => $md5->md5,
                "beta_key" => $beta
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
            echo displayMessage('Bravo, le hash est bon, vous venez de gagner 1 AntoCoin') . PHP_EOL;
        else
            echo displayMessage('Aie, le hash n\'est pas bon, veuillez réessayer') . PHP_EOL;
    }
}

if (stringToLower($argv[1] == 'list')) {

    // Je stock les informations de clé
    $keys = getKey($argv[2]);
    $alpha = $keys->alpha_key;
    $beta = $keys->beta_key;

    // Appel du serveur pour obtenir le hash MD5
    $ch = curl_init();
    try {
        curl_setopt($ch, CURLOPT_URL, "https://127.0.0.1:8000/get_list");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, array(
            "beta_key" => $beta
        ));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $reponseList = curl_exec($ch);
    } catch (\Throwable $th) {
        throw $th;
    } finally {
        curl_close($ch);
    }
    $reponseList = json_decode($reponseList);
    foreach ($reponseList->datas as $data) {
        echo "ID User : " . $data->idUser . PHP_EOL;
        echo "BTC Number : " . $data->btcNumber . PHP_EOL . PHP_EOL;
    }
}
