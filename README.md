CygnusCrypt
===========

Class that can encrypt and decrypt data as well as add base64 encoding and HTML encoding. The encryption is determined by the pin number passed.

$newEncryption = new CygnusCrypt;
$newEncryption->Encrypt($Pin, $textToEncrypt, $baseEncoding, $HTMLEncode);
$newEncryption->encrypt;

$decryption = new CygnusCrypt;
$decryption->Decrypt($Pin, $textToDecrypt, $baseEncoding, $HTMLEncode);
$decryption->encrypt;

To use $baseEncoding or $HTMLEncode set to the number 1 otherwise set to 0

WARNING, if you use the same object to encrypt and decrypt data the latter will overwrite the first.
