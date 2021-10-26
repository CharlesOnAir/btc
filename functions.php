<?php
function displayMessage($message)
{
    echo $message;
}
function stringToLower($string)
{
    return strtolower($string);
}
function createJsonFile($path, $json)
{
    if (!file_put_contents($path, $json))
        return false;
    else
        return true;
}
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
