<?
/*
SecurPay XML Interface Class
----------------------------
This example is based on the SecureXML documentation and should
be used in conjunction with that documentation.
Please feel free to edit this class file to suit your own requirements.
This example comes with no warranty or support. Use at your own risk.

This example was tested on PHP 4.3.7 with register_globals=off

Requirements:
PHP must be compiled with --use-curl.
See http://curl.haxx.se and http://www.php.net/manual/en/ref.curl.php

Usage:
// Edit the variables to set your own merchant id and password

// Include this class and create a new payment object:
include("securepay.class.php");
$obj = new Securepay;

// Set your required input variables:
$obj->setMerchantId($_POST[merchantid]); // Required
$obj->setPassword($_POST[password]); // Required
$obj->setAmount($_POST[amount]); // Required
$obj->setCardnumber($_POST[cardnumber]); // Required
$obj->setExpirydate($_POST[month],$_POST[year]); // Required

// Set your optional input variables:
$obj->setPonum($_POST[ponum]);
$obj->setTest(TRUE);
$obj->setCVV($_POST[cvv]);

// Run the transaction:
$obj->doPayment();

// Retrieve the results:
$status			= $obj->getStatus();
$our_ponum		= $obj->getPonum();
$reponsecode	= $obj->getResponseCode();
$responsetext	= $obj->getResponseText();
$settlementdate	= $obj->getSettlementDate();
$transactionID	= $obj->getTxnID();

*/

class Securepay {

	// There should be no need to touch these
	var $timeout	= 60;
	var $apiVersion = "xml-4.2";
	var $testURL	= "https://www.securepay.com.au/test/payment";
	var $liveURL	= "https://www.securepay.com.au/xmlapi/payment";
	var $result		= array();
	var $error		= array();

	function setMerchantId($merchantid) {
		if (strlen($merchantid) == 7) {
			$this->merchantid = $merchantid;
		} else {
			$this->error[] = "Merchant ID must be of the format ABC0001";
		}
	}

	function setPassword($password) {
		if ($password != "") {
			$this->password = $password;
		} else {
			$this->error[] = "You must set a transaction password";
		}

	}

	function setTest($test) {
		$this->test = $test;
	}

	function setAmount($amount) {
		if ($amount >= 1 and is_numeric($amount) and is_integer($amount*1)) {
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

	function setExpirydate($month, $year) {
		$expiry = $month."/".$year;

		if (strlen($expiry) == 5) {
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
		$this->checkPaymentParameters();

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
		$msg .= '<SecurePayMessage>'."\n";

		$msg .= ' <MessageInfo>'."\n";
		$msg .= '  <messageID>'.$this->setMessageID().'</messageID>'."\n";
		$msg .= '  <messageTimestamp>'.$this->setTimestamp().'</messageTimestamp>'."\n";
		$msg .= '  <timeoutValue>'.$this->timeout.'</timeoutValue>'."\n";
		$msg .= '  <apiVersion>'.$this->apiVersion.'</apiVersion>'."\n";
		$msg .= ' </MessageInfo>'."\n";

		$msg .= ' <MerchantInfo>'."\n";
		$msg .= '  <merchantID>'.$this->merchantid.'</merchantID>'."\n";
		$msg .= '  <password>'.$this->password.'</password>'."\n";
		$msg .= ' </MerchantInfo>'."\n";

		$msg .= ' <RequestType>Payment</RequestType>'."\n";

		$msg .= ' <Payment>'."\n";
		$msg .= '  <TxnList count="1">'."\n";
		$msg .= '   <Txn ID="1">'."\n";
		$msg .= '	<txnType>0</txnType>'."\n";
		$msg .= '	<txnSource>0</txnSource>'."\n";
		$msg .= '	<amount>'.$this->amount.'</amount>'."\n";
		$msg .= '	<currency>'.$this->currencycode.'</currency>'."\n";
		$msg .= '	<purchaseOrderNo>'.$this->ponum.'</purchaseOrderNo>'."\n";
		$msg .= '	<CreditCardInfo>'."\n";
		$msg .= '	 <cardNumber>'.$this->cardnumber.'</cardNumber>'."\n";
		$msg .= '	 <expiryDate>'.$this->expirydate.'</expiryDate>'."\n";
		$msg .= '	 <cvv>'.$this->cvv.'</cvv>'."\n";
		$msg .= '	</CreditCardInfo>'."\n";
		$msg .= '   </Txn>'."\n";
		$msg .= '  </TxnList>'."\n";
		$msg .= ' </Payment>'."\n";

		$msg .= '</SecurePayMessage>';

		return $msg;
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
		$post  = "POST $path HTTP/1.1\r\n";
		$post .= "Host: $host\r\n";
		$post .= "Content-type: application/x-www-form-urlencoded\r\n";
		$post .= "Content-length: ". strlen($msg)."\r\n";
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

			/*******************************************/
			/* Retrieve the HTML headers (and discard) */
			/*******************************************/
			$count = 0;
			while (!feof($h)) {
				$s = fgets($h, 128);
				if ( $s == "\r\n" ) {
					$count++;
					/*******************************************/
					/* Ignore the first \n\n
					/* This check will skip both
					/* HTTP/1.1 100 Continue and 
					/* HTTP/1.1 200 OK headers
					/* Otherwise the loop will just skip the
					/* 100 Continue header and will take
					/* 200 OK and the XML as the response
					/*******************************************/
					if ( $count == 2 ) {
						$foundBody = true;
						continue;
					}
				}
				if ( $foundBody ) {
					$body .= $s;
				} else {
					if ( ($followRedirects) && (stristr($s, "location:") != false) ) {
						$redirect = preg_replace("/location:/i", "", $s);
						return ffl_HttpGet( trim($redirect) );
					}
					$header .= $s;
				}
			}
			$response = $body;
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
		return $this->result[SecurePayMessage][Payment][TxnList][Txn][approved];
	}

	function getResponseCode() {
		return $this->result[SecurePayMessage][Payment][TxnList][Txn][responseCode];
	}

	function getResponseText() {
		return $this->result[SecurePayMessage][Payment][TxnList][Txn][responseText];
	}

	function getSettlementDate() {
		return $this->result[SecurePayMessage][Payment][TxnList][Txn][settlementDate];
	}

	function getTxnID() {
		return $this->result[SecurePayMessage][Payment][TxnList][Txn][txnID];
	}

	function getErrors() {
		return $this->error;
	}
}

?>