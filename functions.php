<?php
// Fonction pour créer une chaine aléatoire

use App\Services\md5;

function genererChaineAleatoire($longueur = 10)
{
    $caracteres = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $longueurMax = strlen($caracteres);
    $chaineAleatoire = '';
    for ($i = 0; $i < $longueur; $i++) {
        $chaineAleatoire .= $caracteres[rand(0, $longueurMax - 1)];
    }
    return $chaineAleatoire;
}
// Fonction en charge d'afficher des messages dans la console
function displayMessage($message)
{
    echo $message;
}
// Fonction en charge de passer en minuscule une chaine de caractère
function stringToLower($string)
{
    return strtolower($string);
}
// Fonction en charge de créer le fichier JSON
function createJsonFile($path, $json)
{
    if (!file_put_contents('token/' . $path, $json))
        return false;
    else
        return true;
}
// Fonction en charge de créer la clé public et privée
function createKeys()
{
    $cleAlpha = genererChaineAleatoire();
    $cleBeta = md5($cleAlpha);
    return json_encode([
        'alpha_key' => $cleAlpha,
        'beta_key' => $cleBeta
    ]);
}
// Fonction en charge de récupérer le résultat final
function getRealKey($md5)
{
    $f = 1;
    for ($i = 0; $i < $f; $i++) {
        $new_hash = md5($f);
        for ($g = 0; $g < 100000; $g++) {
            $new_hash = md5($new_hash);
            if ($new_hash == $md5) {
                return $f;
                break 2;
            }
        }
        $f++;
    }
}
// Fonction pour récupérer les clés
function getKey($path)
{
    return json_decode(file_get_contents('token/' . $path));
}
