<?
class Securepay {

	// There should be no need to touch these
	var $timeout	= 60;
	var $apiVersion = "xml-4.2";
	var $testURL	= "https://www.paymentexpress.com";
	var $liveURL	= "https://www.paymentexpress.com";
	var $result		= array();
	var $error		= array();

	function setMerchantId($merchantid) {
		//if (strlen($merchantid) == 7) {
			$this->merchantid = $merchantid;
		//} else {
		//	$this->error[] = "Merchant ID must be of the format ABC0001";
		//}
	}
	
	function setMerchantRef($ref) {
		//if (strlen($ref) < 0) {
		//	$this->merchantref = $ref;
		//} else {
		//	$this->error[] = "Merchant ID must be of the format ABC0001";
		//}
	}

	function setPassword($password) {
		if ($password != "") {
			$this->password = $password;
		} else {
			$this->error[] = "You must set a transaction password";
		}

	}

	function setTest($test) {
		if($test==TRUE) {
		$this->merchantid="Proferodev";
		$this->password="s0j2mv";
		}
	}

	function setAmount($amount) {
		if ($amount >= 1) {
			$this->amount = $amount;
		} else {
			$this->error[] = "Invalid amount";
		}
	}

	function setPonum($ponum) {
		// Length between 1 and 60, no spaces
		if ((strlen($ponum)>=1 and strlen($ponum)<=60) or !preg_match("/ /", $ponum)) {
			$this->ponum = $ponum;
		} else {
			$this->error[] = "Invalid Purchase Order Number";
		}
	}

	function setCardnumber($cardnumber) {
		// Length between 13 and 16 characters
		if (strlen($cardnumber)>=13 and strlen($cardnumber)<=16 and is_numeric($cardnumber)) {
			$this->cardnumber = $cardnumber;
		} else {
			$this->error[] = "Invalid Credit Card Number";
		}
	}
	
	function setCardname($cardname) {
			$this->cardname = $cardname;
	}

	function setExpirydate($month, $year) {
		$expiry = $month.$year;

		if (strlen($expiry) == 4) {
			$this->expirydate = $expiry;
		} else {
			$this->error[] = "Invalid Expiry Date";
		}
	}

	function setCVV($cvv) {
		// Length between 3 and 4 characters
		if (strlen($cvv)>=3 and strlen($cvv)<=4 and is_numeric($cvv)) {
			$this->cvv = $cvv;
		} else {
			$this->error[] = "Invalid CVV";
		}
	}

	function setCurrencyCode($currencycode) {
		// 3 char ISO Currency Code
		if (strlen($currencycode) == 3) {
			$this->currencycode = $currencycode;
		} else {
			$this->error[] = "Invalid Currency Code";
		}
	}

	function doPayment() {
		//$this->checkPaymentParameters();

		if (sizeof($this->error) < 1) {
			$msg = $this->buildMessage();
			//$this->result = $this->process($msg);
			$this->result = $this->makeXMLTree($this->processSocket($msg));
			
		}
	}

	function checkPaymentParameters() {
		if (isset($this->timeout) and isset($this->apiVersion) and isset($this->merchantid) and isset($this->password) and isset($this->cardnumber) and isset($this->expirydate)) {
			return;
		} else {
			$this->error[] = "Insufficient Payment Parameters";
			return;
		}
	}

	function buildMessage() {
		$msg  = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
		$msg .= '<Txn>'."\n";
		$msg .= '<PostUsername>'.$this->merchantid.'</PostUsername>'."\n";
		$msg .= '<PostPassword>'.$this->password.'</PostPassword>'."\n";
		$msg .= '<Amount>'.$this->amount.'</Amount>'."\n";
		$msg .= '<InputCurrency>'.$this->currencycode.'</InputCurrency>'."\n";
		$msg .= '<CardHolderName>'.$this->cardname.'</CardHolderName>'."\n";
		$msg .= '<CardNumber>'.$this->cardnumber.'</CardNumber>'."\n";
		$msg .= '<DateExpiry>'.$this->expirydate.'</DateExpiry>'."\n";
		$msg .= '<Cvc2>'.$this->cvv.'</Cvc2>';
		$msg .= '<TxnType>Purchase</TxnType>'."\n";
		$msg .= '<MerchantReference>'.$this->merchantref.'</MerchantReference>'."\n";
		$msg .= '</Txn>';

		return $msg;
		//print_r($msg);
		//exit;

	}

	function setMessageID() {
		// An amalgum of timestamp and remote IP address
		$stamp = strtotime ("now"); 
		$messageid = "$stamp$_SERVER[REMOTE_ADDR]"; 
		$messageid = str_replace(".", "", "$messageid");

		return $messageid;
	}

	function setTimestamp() {
		$stamp = date("YmdGis")."000+600";
		return $stamp;
	}

	/**************************/
	/* Secure Socket Function */
	/**************************/
	function processSocket($msg){

		// NOTE. Apache on Windows can generate the following warning
		// Warning: fgets(): SSL: fatal protocol error in ...
		// This is not really fatal so we set the following:
		error_reporting(E_ERROR | E_PARSE);

		if ($this->test)
			$host = $this->testURL;
		else
			$host = $this->liveURL;

		// Break the URL into usable parts
		$path = explode('/',$host);


		$host = $path[2];
		unset($path[0], $path[1], $path[2]);
		$path = '/'.(implode('/',$path));

		// i.e.
		// $host = 'www.securepay.com.au';
		// $path = '/xmlapi/payment';

		// Prepare the post query
		$post  = "POST /pxpost.aspx HTTP/1.1\r\n";
		$post .= "Host: ".$host."\r\n";
		$post .= "Content-type: application/x-www-form-urlencoded\r\n";
		$post .= "Content-length: ". strlen($msg)."\r\n";
		$post .= "Accept: */*\r\n";
		$post .= "Connection: close\r\n\r\n$msg";

		/***********************************************/
		/* Open the secure socket and post the message */
		/***********************************************/
		$h = fsockopen("ssl://".$host, 443, $errno, $errstr);
//		if ($errstr) {
		if (!$h) {
			$response = "Error: ".$errstr." (".$errno.")";
		} else {
			fwrite($h,$post);
			fflush($h);
			

			/*******************************************/
			/* Retrieve the HTML headers (and discard) */
			/*******************************************/
			$count = 0;
			while (!feof($h)) { $response.=fread($h, 128); }
			
			$start = strpos($response,'<Txn>');
			$end = strpos($response,'</Txn>');
			$response = substr($response,$start,$end-$start+6);
			
			//echo $response;
		}
		// Close the socket
		fclose($h);

		// Return the body of the response
		// This will either be the transaction response
		// or an error string.
		return $response;
		
		
	}

	function makeXMLTree ($data) {
	   $output = array();
	   
	   $parser = xml_parser_create();

	   xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
	   xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
	   xml_parse_into_struct($parser, $data, $values, $tags);
	   xml_parser_free($parser);
	   
	   $hash_stack = array();
	   
	   foreach ($values as $key => $val)
	   {
		   switch ($val['type'])
		   {
			   case 'open':
				   array_push($hash_stack, $val['tag']);
				   break;
		   
			   case 'close':
				   array_pop($hash_stack);
				   break;
		   
			   case 'complete':
				   array_push($hash_stack, $val['tag']);
				   eval("\$output['" . implode($hash_stack, "']['") . "'] = \"{$val['value']}\";");
				   array_pop($hash_stack);
				   break;
		   }
	   }
		
		//print_r($output);
	   return $output;
   }
   
	function getAll() {
   		return $this->result;
   	}

	function getTransactionStatusCode() {
		return $this->result[SecurePayMessage][Status][statusCode];
	}

	function getTransactionStatusText() {
		return $this->result[SecurePayMessage][Status][statusDescription];
	}

	function getPonum() {
		return $this->result[SecurePayMessage][Payment][TxnList][Txn][purchaseOrderNo];
	}

	function isApproved() {
		return $this->result[Txn][Success];
	}

	function getResponseCode() {
		return $this->result[SecurePayMessage][Payment][TxnList][Txn][responseCode];
	}

	function getResponseText() {
		return $this->result[Txn][ResponseText];
	}

	function getSettlementDate() {
		return $this->result[SecurePayMessage][Payment][TxnList][Txn][settlementDate];
	}

	function getTxnID() {
		return $this->result[Txn][Transaction][DpsTxnRef];
	}

	function getErrors() {
		return $this->error;
	}
}

?>