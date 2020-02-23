<?php
date_default_timezone_set('Asia/Calcutta');
$datenow = date("d/m/Y h:m:s");
$transactionDate = str_replace(" ", "%20", $datenow);

$transactionId = rand(1,1000000);

// require_once 'TransactionRequest.php';
include_once(APPPATH.'core/TransactionRequest.php');

$transactionRequest = new TransactionRequest();

//Setting all values here
$transactionRequest->setMode(isset($pg['mode']) ? $pg['mode'] : 'test');
$transactionRequest->setLogin(isset($pg['login']) ? $pg['login'] : '');
$transactionRequest->setPassword(isset($pg['password']) ? $pg['password'] : '');
$transactionRequest->setProductId(isset($pg['productinfo']) ? $pg['productinfo'] : '');
$transactionRequest->setAmount(isset($pg['amount']) ? floatval($pg['amount']) : 0.00);
$transactionRequest->setTransactionCurrency(isset($pg['currency']) ? $pg['currency'] : '');
$transactionRequest->setTransactionAmount(isset($pg['transaction_amount']) ? $pg['transaction_amount'] : '');
//$transactionRequest->setReturnUrl("https://oxytra.com/atompay/response.php");
$transactionRequest->setReturnUrl(isset($pg['return_url']) ? $pg['return_url'] : '');
$transactionRequest->setClientCode(isset($pg['client_code']) ? $pg['client_code'] : '');
$transactionRequest->setTransactionId(isset($pg['txnid']) ? $pg['txnid'] : '');
$transactionRequest->setTransactionDate(isset($pg['txndate']) ? $pg['txndate'] : '');
$transactionRequest->setCustomerName(isset($pg['name']) ? $pg['name'] : '');
$transactionRequest->setCustomerEmailId(isset($pg['email']) ? $pg['email'] : '');
$transactionRequest->setCustomerMobile(isset($pg['mobile']) ? $pg['mobile'] : '');
$transactionRequest->setCustomerBillingAddress(isset($pg['billing_city']) ? $pg['billing_city'] : '');
$transactionRequest->setCustomerAccount(isset($pg['customer_account']) ? $pg['customer_account'] : '');
$transactionRequest->setReqHashKey(isset($pg['key']) ? $pg['key'] : '');

// $transactionRequest->setSalt(isset($pg['salt']) ? $pg['salt'] : '');
// This is for encrypted post
// $transactionRequest->seturl("https://paynetzuat.atomtech.in/paynetz/epi/fts");
// $transactionRequest->setRequestEncypritonKey("8E41C78439831010F81F61C344B7BFC7");
// $transactionRequest->setSalt("8E41C78439831010F81F61C344B7BFC7");

$url = $transactionRequest->getPGUrl();
header("Location: $url");