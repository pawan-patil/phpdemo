<?php
error_reporting(E_ALL ^ E_NOTICE);
//header('Content-Type: application/json');
$json_decode = json_decode(file_get_contents("php://input"),true);
$get_action = $json_decode['result']['action'];
if($get_action=='yahooWeatherForecast'){
	
	$sessionid = $json_decode['sessionId'];
	$location = $json_decode['result']['parameters']['geo-city'];
	$link = urlencode("select * from weather.forecast where woeid in (select woeid from geo.places(1) where text='$location')");
    $baseurl = "https://query.yahooapis.com/v1/public/yql?q=$link&format=json";
   
    $waether_response = json_decode(file_get_contents($baseurl),true);
	
	$speech = "Today in ".$waether_response['query']['results']['channel']['location']['city'].": ".$waether_response['query']['results']['channel']['item']['condition']['text'].", the temperature is ".$waether_response['query']['results']['channel']['item']['condition']['temp']." ".$waether_response['query']['results']['channel']['units']['temperature'];
	$data['fulfillment']['speech'] = $speech;
	$data['fulfillment']['source'] = 'phpdemo';
	$data['fulfillment']['displayText'] = $speech;
	
	//$data['status']['code'] = 200;
	//$data['status']['errorType'] = 'success';
	
	//$data['sessionId'] = $sessionid;
	
	header('Content-Type: application/json');
	
	echo json_encode($data);
	
} else {
	
	echo '{}';
}
?>