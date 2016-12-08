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
	
	$data['status']['code'] = 200;
	$data['status']['errorType'] = 'success';
	
	$data['sessionId'] = $sessionid;
	
	//header('Content-Type: application/json');
	$str = '{
  "id": "c720e1a5-ea95-4995-a7dd-e7829b77271c",
  "timestamp": "2016-12-08T10:11:24.472Z",
  "result": {
    "source": "agent",
    "resolvedQuery": "weather in pune",
    "action": "yahooWeatherForecast",
    "actionIncomplete": false,
    "parameters": {
      "date": "",
      "geo-city": "Pune"
    },
    "contexts": [],
    "metadata": {
      "intentId": "97517aad-49e2-46f1-8e4e-9e0136a535cc",
      "webhookUsed": "true",
      "webhookForSlotFillingUsed": "false",
      "intentName": "weather"
    },
    "fulfillment": {
      "speech": "Today in Pune: Sunny, the temperature is 83 F",
      "source": "apiai-weather-webhook-sample",
      "displayText": "Today in Pune: Sunny, the temperature is 83 F",
      "messages": [
        {
          "type": 0,
          "speech": "Today in Pune: Sunny, the temperature is 83 F"
        }
      ]
    },
    "score": 1
  },
  "status": {
    "code": 200,
    "errorType": "success"
  },
  "sessionId": "48b12c4a-2531-4183-b6df-178028078c68"
}';
	echo $str;
	//echo json_encode($data);
	
} else {
	
	echo '{}';
}
?>