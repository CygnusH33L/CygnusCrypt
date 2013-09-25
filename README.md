CygnusCrypt
===========

Class that can encrypt and decrypt data as well as add base64 encoding and HTML encoding. The encryption is determined by the pin number passed.

################################################################################
################################################################################
##########################                   ###################################
##########################    CygnusCrypt    ###################################
##########################                   ###################################
################################################################################
################################################################################
// This class will encrypt/decrypt data that is passed to it. It has the ability 
// to add base64 encoding for added protection and to encode the output strings
// as HTML entities for use on HTML pages. The methods require a pin number
// to be passed to it.
################################################################################
##########################       Usage        ##################################
################################################################################
//
// $newEncryption = new CygnusCrypt;
// $newEncryption->Encrypt($Pin, $textToEncrypt, $baseEncoding, $HTMLEncode);
// $newEncryption->encrypt;
//
// $decryption = new CygnusCrypt;
// $decryption->Decrypt($Pin, $textToDecrypt, $baseEncoding, $HTMLEncode);
// $decryption->encrypt;
//
// To use $baseEncoding or $HTMLEncode set to the number 1 otherwise set to 0
//
// *****************************************************************************
// WARNING, if you use the same object to encrypt and decrypt data the latter 
// will overwrite the first.
// *****************************************************************************
