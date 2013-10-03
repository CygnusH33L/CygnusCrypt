<html>
<head>
<titl>Example Usage.</title>
</head>
<body>

<?php
require("CygnusCrypt v2.3.php");

// variables
$data = "foobar";
$pin = 7346;
$base64Encode = 1; // 1 for true 0 for false
$HTMLEncode = 0; // 1 use HTMLEntities() on output 0 not to

// secrets (if you're not using the defaults)
$secretPin = 5423; // must be numeric
$secret = "hfsgf74hjfr"; // can be mixed
$secret2 = "jfshkf34hfdh";
$secret3 = "fhjs8ahjf";

// call the class
$newEncryption = new CygnusCrypt;

// add your own secrets (returns object so you can chain methods)
$newEncryption->setSecrets($secretPin, $secret, $secret2, $secret3)->Encryption($pin, $data, $base64Encode, $HTMLEncode);

// encrypt the data (if you don't set your own secrets they will be set for you.)
$newEncryption->Encryption($pin, $data, $base64Encode, $HTMLEncode);

// display encrypted data
echo $newEncryption->encrypt;

$encryptedData = $newEncryption->encrypt;

// create a new object or it will overwrite the encryption output
$newDecryption = new CygnusCrypt;

// Decrypt using your own secrets
$newDecryption->setSecrets($secretPin, $secret, $secret2, $secret3)->Decrypt($pin, $encryptedData, $base64Encode, $HTMLEncode);

// Decrypt the data using the default secrets
$newDecryption->Decrypt($pin, $encryptedData, $base64Encode, $HTMLEncode);

// display decrypted data
echo $newDecryption->encrypt;

?>

</body>
</html>
