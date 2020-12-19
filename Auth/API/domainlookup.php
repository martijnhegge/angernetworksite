<?php

$domain = 'google.com';
$password = 'quibh5m9';
$username = 'martijnhegge@outlook.com';

$url = 'http://www.whoisxmlapi.com/whoisserver/WhoisService?outputformat=JSON&domainName='.$domain.'&username='.$username.'&password='.$password;

$json = (file_get_contents($url));
print("<pre>".var_dump(json_decode($json, true))."</pre>");
//var_dump(json_decode($json, true));
$obj = json_decode($json, TRUE);
print $obj->WhoisRecord[0];
/*$username = 'martijnhegge@outlook.com';
$apiKey = 'at_7HHAwXbLMtdaChVyljr23ze5OTwm5';
$secret = 'Your whois api secret key';

$url = 'https://whoisxmlapi.com/whoisserver/WhoisService';

$timestamp = null;

$domains = array(
    'google.com',
    'example.com',
    'whoisxmlapi.com',
    'twitter.com',
);

$digest = null;

generate_params($timestamp, $digest, $username, $apiKey, $secret);

foreach ($domains as $domain) {
    $response = request($url, $username, $timestamp, $digest, $domain);

    if (strpos($response, 'Request timeout') !== false) {
        generate_params($timestamp, $digest, $username, $apiKey, $secret);
        $response = request($url, $username, $timestamp, $digest, $domain);
    }

    print_response($response);
    echo '----------------------------' . "\n";
}

function generate_params(&$timestamp, &$digest, $username, $apiKey, $secret)
{
    $timestamp = round(microtime(true) * 1000);
    $digest = generate_digest($username, $timestamp, $apiKey, $secret);
}

function request($url, $username, $timestamp, $digest, $domain)
{
    $requestString = build_request($username, $timestamp, $digest, $domain);

    return file_get_contents($url . $requestString);
}

function print_response($response)
{
    $responseArray = json_decode($response, true);

    if (! empty($responseArray['WhoisRecord']['createdDate'])) {
        echo 'Created Date: '
             . $responseArray['WhoisRecord']['createdDate']
             . PHP_EOL;
    }

    if (! empty($responseArray['WhoisRecord']['expiresDate'])) {
        echo 'Expires Date: '
             . $responseArray['WhoisRecord']['expiresDate']
             . PHP_EOL;
    }

    if (! empty($responseArray['WhoisRecord']['registrant']['rawText'])) {
        echo $responseArray['WhoisRecord']['registrant']['rawText'] . PHP_EOL;
    }
}

function generate_digest($username, $timestamp, $apiKey, $secretKey)
{
    $digest = $username . $timestamp . $apiKey;
    $hash = hash_hmac('md5', $digest, $secretKey);

    return urlencode($hash);
}

function build_request($username, $timestamp, $digest, $domain)
{
    $request = array(
        'u' => $username,
        't' => $timestamp
    );

    $requestJson = json_encode($request);
    $requestBase64 = base64_encode($requestJson);

    $requestString = '?requestObject=' . urlencode($requestBase64)
                   . '&digest=' . $digest
                   . '&domainName=' . urlencode($domain)
                   . '&outputFormat=json';

    return $requestString;
}
/*session_start();
    ob_start();
    $con = new database;
    $con->connect();*/
//$host = $_GET['host'];
/*$url = "https://www.whoisxmlapi.com/whoisserver/WhoisService?apiKey=at_7HHAwXbLMtdaChVyljr23ze5OTwm5&domainName={$host}&outputFormat=JSON";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_NOSIGNAL, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_TIMEOUT, 1);
 curl_exec($ch);
curl_close($ch);
$response = json_decode($json_response, true);
print_r($response);*/

//$curl = curl_init();
/*
curl_setopt_array($curl, array(
	CURLOPT_URL => "https://www.whoisxmlapi.com/whoisserver/WhoisService?apiKey=at_7HHAwXbLMtdaChVyljr23ze5OTwm5&domainName={$host}&outputFormat=JSON",
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_FOLLOWLOCATION => true,
	CURLOPT_ENCODING => "",
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 30,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => "GET",
	CURLOPT_HTTPHEADER => array(
		"x-rapidapi-host: whoisapi-whois-v2-v1.p.rapidapi.com",
		"x-rapidapi-key: at_7HHAwXbLMtdaChVyljr23ze5OTwm5"
	),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
	echo "cURL Error #:" . $err;
} else {
	echo $response;
}*/
?>