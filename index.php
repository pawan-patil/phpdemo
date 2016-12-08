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
	$data['fulfillment']['source'] = 'apiai-weather-webhook-sample1';
	$data['fulfillment']['displayText'] = $speech;
	
	$data['status']['code'] = 206;
	$data['status']['errorType'] = 'fail';
	
	$data['sessionId'] = $sessionid;
	
	header('Content-Type: application/json');
	$str ='{
  "id": "140d135a-831f-4a1e-b51c-4db8d92bdaea",
  "timestamp": "2016-12-08T09:19:19.727Z",
  "result": {
    "source": "agent",
    "resolvedQuery": "weather in pune",
    "action": "yahooWeatherForecast11",
    "actionIncomplete": false,
    "parameters": {
      "date": "",
      "geo-city": "Pune"
    },
    "contexts": [],
    "metadata": {
      "intentId": "eefbb38e-8a96-4c5b-8943-951bd4af3ac4",
      "webhookUsed": "true",
      "webhookForSlotFillingUsed": "false",
      "intentName": "weather"
    },
    "fulfillment": {
      "speech": "Today in Pune: Sunny, the temperature is 83 F",
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
  "sessionId": "3b11b03a-28b7-4709-833f-45f60f5ebffa"
}';
	//echo json_encode($data);
	echo $str;
	
} else {
	
	echo '{}';
}
?>