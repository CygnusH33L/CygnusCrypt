<?php
################################################################################
################################################################################
#########################                     ##################################
#########################  CygnusCrypt v2.5.1 ##################################
#########################                     ##################################
################################################################################
################################################################################
//
// Author: CygnusH33L
// Author wbesite: CygnusH33L.co.uk
// Donations: Bitcoins 1DC7A7YZbanqJ4QgKFbbtjKiyLH93eWHiJ
//
################################################################################
##########################     Description    ##################################
################################################################################
//
// This class will encrypt/decrypt data that is passed to it. It has the ability 
// to add base64 encoding for added protection and to encode the output strings
// as HTML entities for use on HTML pages. The methods require a pin number
// to be passed to it.
//
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

class CygnusCrypt {
	
	// create variables
	private $letters;
	private $pin;
	private $numSteps;
	private $secretPin;
	private $random;
	private $random2;
	private $random3;
	private $secretSet;
	
	var $output;
		
	// *******************************************************
	// **************** default secrets **********************
	// *******************************************************
	// when creating the object tell it to use the defualt secrets
	function __construct() {
		$this->secretSet = 0;
	}
		
	// *******************************************************
	// ************* set characters array ********************
	// *******************************************************
	// All characters contained in this array are able to be encrypted, 
	// use only printable characters from the ASCII table
	private function Algo() {
		$this->letters = array("!", " ", "\"", "#", "$", "%", "&", "\\", "'", "(", ")", "*", "+", ",", "-", ".", "/", "0", "1", "2", "3", "4", "5", "6", "7", "8", "9", ":", ";", "<", "=", ">", "?", "@", "A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z", "[", "]", "^", "_", "`", "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z", "{", "|", "}", "~");
		return $this;
	}
	
	// *******************************************************
	// ***************** Set Secrets *************************
	// *******************************************************
	public function setSecrets($pin, $secret1, $secret2, $secret3) {
	if(empty($pin) or empty($secret1) or empty($secret2) or empty($secret3)) {
		exit("One or more secrets are missing.");
	}
	if(!is_numeric($pin)) {
		exit("Secret pin must be numeric.");
	}
	$this->secretPin = $pin;
	$this->random = $secret1;
	$this->random2 = $secret2;
	$this->random3 = $secret3;
	$this->secretSet = 1;
	return $this;
	}
	
	// *******************************************************
	// ************* Find Step position **********************
	// *******************************************************
	// work out character step (make sure it is always less than or equal to the amount of characters)
	private function Step() {
		$this->Algo();
		$num = count($this->letters);
		$this->numSteps = $this->pin;
		$this->numSteps = round($this->numSteps);
		while($this->numSteps > $num) {
			$this->numSteps = $this->numSteps / 3;
			$this->numSteps = round($this->numSteps);	
		}		
		return $this->numSteps;
	}
	
	// *******************************************************
	// *************** Split and Encrypt *********************
	// *******************************************************
	// Split the string into chuncks and scramble characters
	// then join string
	private function SplitCrypt () {
		$length = strlen($this->output);
		$splitBy = round($length / 4);
		$chunks = str_split($this->output, $splitBy);
		foreach($chunks as $chunk) {
			$chunk = $this->CygEncrypt($chunk);	
		}
		$this->output = join($chunks, "");
		$this->output = $this->output.$this->random.$splitBy.$this->random2;
		return $this->output;		
	}
	
	// *******************************************************
	// ************** Decrypt and join ***********************
	// *******************************************************
	// Split string into chuncks and unscramble chunks
	// then join string
	private function SplitDecrypt() {
		$splitBy = 0;
		if(preg_match_all('/$this->random(.*)$this->random2/U', $this->output, $match)) {
			$splitBy = $match[0];	
		}
		$splitBy = str_replace($this->random, "", $splitBy[0]);
		$splitBy = str_replace($this->random2, "", $splitBy);

		if($splitBy == 0) {
			$splitBy = 1;
		}
		$this->output = preg_replace("/$this->random(.*)$this->random2/U", "", $this->output);
		$length = strlen($this->output);
		$chunks = str_split($this->output, $splitBy);
		foreach($chunks as $chunk) {
			$chunk = $this->CygDecrypt($chunk);
		}
		$this->output = join($chunks, "");
		return $this->output;			
	}
	
	// *******************************************************
	// ******* perform character step (encrypt data) *********
	// *******************************************************
	// Scramble passed string
	private function CygEncrypt($encrypt) {
		$this->Algo();
		$encrypt = str_split($encrypt);
		for ($x = 0; $x < count($encrypt); $x++) {
			if(in_array($encrypt[$x], $this->letters)) {
				$pos = array_search($encrypt[$x], $this->letters);
				$newpos = $pos + $this->numSteps;
				$numLetters = count($this->letters);
				if($newpos >= $numLetters) {
					$newpos = $newpos - $numLetters;
				}
				$encrypt[$x] = $this->letters[$newpos];
			}
		}
		$encrypt = join($encrypt, "");
		return $encrypt;
	}

	// *******************************************************
	// ******* perform character step (encrypt data) *********
	// *******************************************************
	// unscramble passed string
	private function CygDecrypt($encrypt) {
		$this->Algo();
		$encrypt = str_split($encrypt);
		for ($x = 0; $x < count($encrypt); $x++) {
			if(in_array($encrypt[$x], $this->letters)) {
				$pos = array_search($encrypt[$x], $this->letters);
				$newpos = $pos - $this->numSteps;
				$numLetters = count($this->letters);
				if($newpos < "0") {
					$newpos = $newpos + $numLetters;
				}
				if($newpos == $numLetters) {
					$newpos = "0";	
				}
				$encrypt[$x] = $this->letters[$newpos];
			}
		}
		$encrypt = join($encrypt, "");
		return $encrypt;
	}
	
	// *****************************************************
	// **************** create pin *************************
	// *****************************************************
	// create a secret pin number, this will be added to the encryption
	// and also determines the letter step (scramble).
	private function CreatePin($pin) {
		$this->Algo();
		if(!is_numeric($pin)) {
			$splitPin = str_split($pin);
			foreach ($splitPin as $swap) {
				if(in_array($swap, $this->letters)) {
					$pin = array_search($swap, $this->letters);
				}
			}
		}
			$this->pin = $pin * $this->secretPin;
		return $this;
	}
	
	// *****************************************************
	// ***************** Pin Check *************************
	//******************************************************
	// This adds a string of characters to the encryption which
	// then is checked for before decrypting to see if a pin was used
	## ** Attention, this is no longer used for a pin check, it now
	## ** only adds an extra layer to the encryption
	private function PinUsed() {
		$this->output = str_split($this->output);
		$length = count($this->output);
		$randPosition = rand(0, $length);
		// add check to stop undefined offset error
		if($randPosition == $length) {
			$randPosition = $randPosition - 1;
		}
		$this->output[$randPosition] = $this->random3.$this->output[$randPosition];
		$this->output = join($this->output, "");
		return $this->output;
	}
	
	// ****************************************************
	// ************** add/encrypt pin *********************
	// ****************************************************
	// fucntion to add pin to encrypted data
	private function PinEncrypt() {
		$this->output = $this->CygEncrypt($this->output);
		$this->output = $this->pin.$this->output;
		$this->output = base64_encode($this->output);
		$this->output = $this->PinUsed();
		return $this;
	}
	
	// ****************************************************
	// ************* public encrypt function **************
	// ****************************************************
	public function Encrypt($pin, $encryptThis, $base, $htmlEncode) {
		if(empty($encryptThis)) {
			exit("<br /><br /><span style='text-align:center; font-weight:bold;'>Please enter some text to encrypt.</span><br /><br />");
		}
		if(empty($pin)) {
			exit("<br /><br /><span style='text-align:center; font-weight:bold;'>A pin or password is required.</span></br /><br />");
		}
		
		if($this->secretSet == 0) {
			$this->setSecrets("95781", "cucuegjs", "ufwhcjsh", "sdgHJDgsdf");
		}
	
		$this->CreatePin($pin);
		$this->Step();
		$this->output = $encryptThis;
		$this->output = $this->SplitCrypt();
		
		$this->PinEncrypt();
		
		$this->output = $this->CygEncrypt($this->output);
		if($base == "1") {
			$this->output = base64_encode($this->output);	
		}
		
		if($htmlEncode == "1") {
			$this->output = htmlentities($this->output);
		}
		return $this->output;
	}
	
	// ****************************************************
	// *********** public decrypt function ****************
	// ****************************************************
	public function Decrypt($pin, $decryptThis, $base, $htmlEncode) {
		if(empty($decryptThis)) {
			exit("<br /><br /><span style='text-align:center; font-weight:bold;'>Please enter some text to decrypt.</span><br /><br />");
		}
		if(empty($pin)) {
			exit("<br /><br /><span style='text-align:center; font-weight:bold;'>A pin or password is required.</span><br /><br />");
		}
		
		if($this->secretSet == 0) {
			$this->setSecrets("95781", "cucuegjs", "ufwhcjsh", "sdgHJDgsdf");
		}
		
		$this->output = $decryptThis;
		if($base == "1") {
			$this->output = base64_decode($this->output);
		}
		$this->CreatePin($pin);
		$this->Step();
		$this->output = $this->CygDecrypt($this->output);
		
		if(strpos("x".$this->output, $this->random3) !== false) {
			$this->output = preg_replace("/$this->random3/", "", $this->output, 1);
			$this->output = base64_decode($this->output);
			$this->output = str_replace($this->pin, "", $this->output);
			$this->output = $this->CygDecrypt($this->output);
		} else {
			exit("<br /><br /><span style='text-align:center; font-weight:bold;'>Incorrect Pin or Password.</span><br /><br />");
		}
		
		$this->output = $this->SplitDecrypt();
		
		if($htmlEncode == "1") {
			$this->output = htmlentities($this->output);
		}
		return $this->output;
	}

} // end class
?>
