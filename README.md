CygnusCrypt
===========

Class that can encrypt and decrypt data as well as add base64 encoding and HTML encoding. The encryption is determined by the pin number, password or mixed password passed.

$newEncryption = new CygnusCrypt;
// set your own secrets
$newEncryption->setSecrets($secretPin, $secret, $secret2, $secret3)->Encryption($pin, $data, $base64Encode, $HTMLEncode);
// use the default secrets
$newEncryption->Encrypt($Pin, $textToEncrypt, $baseEncoding, $HTMLEncode);
// output the encrypted data
$newEncryption->output;

$decryption = new CygnusCrypt;
// set your own secrets
$newDecryption->setSecrets($secretPin, $secret, $secret2, $secret3)->Decrypt($pin, $encryptedData, $base64Encode, $HTMLEncode);
// use the default secrets
$decryption->Decrypt($Pin, $textToDecrypt, $baseEncoding, $HTMLEncode);
// output the decrypted data
$decryption->output;

To use $baseEncoding or $HTMLEncode set to the number 1 otherwise set to 0

WARNING, if you use the same object to encrypt and decrypt data the latter will overwrite the first.
