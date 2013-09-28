<?php
################################################################################
################################################################################
##########################                   ###################################
########################## CygnusCrypt v2.4  ###################################
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

class CygnusCrypt {
	
	// create variables
	private $letters;
	private $pin;
	private $numSteps;
	var $encrypt;
		
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
	private function SplitCrypt () {
		$length = strlen($this->encrypt);
		$splitBy = round($length / 4);
		$chunks = str_split($this->encrypt, $splitBy);
		foreach($chunks as $chunk) {
			$chunk = $this->CygEncrypt($chunk);	
		}
		$this->encrypt = join($chunks, "");
		$this->encrypt = $this->encrypt."cucuegjs".$splitBy."ufwhcjsh";
		return $this->encrypt;		
	}
	
	// *******************************************************
	// ************** Decrypt and join ***********************
	// *******************************************************
	private function SplitDecrypt() {
		$splitBy = 0;
		if(preg_match_all('/cucuegjs(.*)ufwhcjsh/U', $this->encrypt, $match)) {
			$splitBy = $match[0];	
		}
		$splitBy = str_replace("cucuegjs", "", $splitBy[0]);
		$splitBy = str_replace("ufwhcjsh", "", $splitBy);

		if($splitBy == 0) {
			$splitBy = 1;
		}
		$this->encrypt = preg_replace("/cucuegjs(.*)ufwhcjsh/U", "", $this->encrypt);
		$length = strlen($this->encrypt);
		$chunks = str_split($this->encrypt, $splitBy);
		foreach($chunks as $chunk) {
			$chunk = $this->CygDecrypt($chunk);
		}
		$this->encrypt = join($chunks, "");
		return $this->encrypt;			
	}
	
	// *******************************************************
	// ******* perform character step (encrypt data) *********
	// *******************************************************
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
	// and also determines the letter step.
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
			$this->pin = $pin * 95781;
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
		$this->encrypt = str_split($this->encrypt);
		$length = count($this->encrypt);
		$randPosition = rand(0, $length);
		// add check to stop undefined offset error
		if($randPosition == $length) {
			$randPosition = $randPosition - 1;
		}
		$this->encrypt[$randPosition] = "sdgHJDgsdf".$this->encrypt[$randPosition];
		$this->encrypt = join($this->encrypt, "");
		return $this->encrypt;
	}
	
	// ****************************************************
	// ************** add/encrypt pin *********************
	// ****************************************************
	// fucntion to add pin to encrypted data
	private function PinEncrypt() {
		$this->encrypt = $this->CygEncrypt($this->encrypt);
		$this->encrypt = $this->pin.$this->encrypt;
		$this->encrypt = base64_encode($this->encrypt);
		$this->encrypt = $this->PinUsed();
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
	
		$this->CreatePin($pin);
		$this->Step();
		$this->encrypt = $encryptThis;
		$this->encrypt = $this->SplitCrypt();
		
		$this->PinEncrypt();
		
		$this->encrypt = $this->CygEncrypt($this->encrypt);
		if($base == "1") {
			$this->encrypt = base64_encode($this->encrypt);	
		}
		
		if($htmlEncode == "1") {
			$this->encrypt = htmlentities($this->encrypt);
		}
		return $this->encrypt;
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
		
		$this->encrypt = $decryptThis;
		if($base == "1") {
			$this->encrypt = base64_decode($this->encrypt);
		}
		$this->CreatePin($pin);
		$this->Step();
		$this->encrypt = $this->CygDecrypt($this->encrypt);
		
		if(strpos("x".$this->encrypt, "sdgHJDgsdf") !== false) {
			$this->encrypt = preg_replace("/sdgHJDgsdf/", "", $this->encrypt, 1);
			$this->encrypt = base64_decode($this->encrypt);
			$this->encrypt = str_replace($this->pin, "", $this->encrypt);
			$this->encrypt = $this->CygDecrypt($this->encrypt);
		} else {
			exit("<br /><br /><span style='text-align:center; font-weight:bold;'>Incorrect Pin or Password.</span><br /><br />");
		}
		
		$this->encrypt = $this->SplitDecrypt();
		
		if($htmlEncode == "1") {
			$this->encrypt = htmlentities($this->encrypt);
		}
		return $this->encrypt;
	}

} // end class
?>
