
<?php

$access_token = 'OJ6/foDdVGANW67BYDBP16uIyAvEwA/c9zIX443Ko83JZlwN5+jmCuy7IEKKp3VJXrHb9Q54qcB+ELC9xv6T+0uUY7oHehghIahq1BKCnFMzy1pVfyOeE/cuy0rzU7hDd+lAnqFGvM8st1gwB8BAGwdB04t89/1O/w1cDnyilFU=';
// Get POST body content
$content = file_get_contents('php://input');
// Parse JSON
$events = json_decode($content, true);

function checkText($txt) {	
 
	$_word = explode(" ", trim($txt));
	if(count($_word) >= 2){
		
		$_arrkeyword = array("ACTIVE", "Active", "active");
		if (in_array($_word[0], $_arrkeyword)) {
			$circuitid = $_word[1];
			$_msg = " Active Circuit ID :  ".$circuitid;
		}else{
			$_msg = "Not IN template1";
		}

		
	}else{
		$_msg = "Not IN template2";
	}  
	
	return $_msg;

}


// Validate parsed JSON data
if (!is_null($events['events'])) {
	// Loop through each event
	foreach ($events['events'] as $event) {
		// Reply only when message sent is in 'text' format
		if ($event['type'] == 'message' && $event['message']['type'] == 'text') {
			// Get text sent
			$_text = $event['message']['text'];
			$text = checkText($_text);
			// Get replyToken
			$replyToken = $event['replyToken'];

			// Build message to reply back
			$messages = [
				'type' => 'text',
				'text' => $text
			];

			// Make a POST Request to Messaging API to reply to sender
			$url = 'https://api.line.me/v2/bot/message/reply';
			$data = [
				'replyToken' => $replyToken,
				'messages' => [$messages],
			];
			$post = json_encode($data);
			$headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);

			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			$result = curl_exec($ch);
			curl_close($ch);

			echo $result . "\r\n";
		}
	}
}
echo "OK";

?>
