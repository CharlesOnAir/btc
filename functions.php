<?php
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
    if (!file_put_contents($path, $json))
        return false;
    else
        return true;
}
// Fonction en charge de créer la clé public et privée
function createKeys()
{
    $config = array(
        "digest_alg" => "sha512",
        "private_key_bits" => 4096,
        "private_key_type" => OPENSSL_KEYTYPE_RSA,
    );
    $res = openssl_pkey_new($config);
    openssl_pkey_export($res, $private_key);
    $public_key = openssl_pkey_get_details($res);
    $public_key = $public_key["key"];
    return json_encode([
        'public_key' => $public_key,
        'private_key' => $private_key
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
