<html>
<head>
<titl>Example Usage.</title>
</head>
<body>

<?php
require("CygnusCrypt v2.3.php");

$data = "foobar";
$pin = 7346;
$base64Encode = 1; // 1 for true 0 for false
$HTMLEncode = 0; // 1 use HTMLEntities() on output 0 not to

// call the class
$newEncryption = new CygnusCrypt;

// encrypt the data
$newEncryption->Encryption($pin, $data, $base64Encode, $HTMLEncode);

// display encrypted data
echo $newEncryption->encrypt;

$encryptedData = $newEncryption->encrypt;

// create a new object or it will overwrite the encryption output
$newDecryption = new CygnusCrypt;

// Decrypt the data
$newDecryption->Decrypt($pin, $encryptedData, $base64Encode, $HTMLEncode);

// display decrypted data
echo $newDecryption->encrypt;

?>

</body>
</html>
