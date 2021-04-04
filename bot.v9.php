<?php
	$data = file_get_contents('php://input');
	$data = json_decode($data, true);

	$m = ['inline_keyboard' => [[
			['text' => 'ok', 'callback_data' => 1],
			['text' => 'подключить оператора', 'callback_data' => 4],
			['text' => 'cancel', 'callback_data' => 0]]]];
			
	$m2 = ['inline_keyboard' => [[
			['text' => 'вперед', 'callback_data' => 2],
			['text' => 'назад', 'callback_data' => 3]]]];
	
	$m3 = ['inline_keyboard' => [[
			['text' => 'завершить диалог', 'callback_data' => 5]]]];			

	if(array_key_exists("callback_query", $data)):
		$chat_id = $data['callback_query']['message']['chat']['id'];
		//кнопки
		switch ($data['callback_query'][data]) 
		{
			case 1: $m = $m2; break;
			case 4: $m = $m3; break;
        }
		//кнопки
		$text = $data['callback_query'][data];
		$message_id = $data['callback_query']['message']['message_id'];
	else:
		$text = 'empty';
		$chat_id = $data['message']['chat']['id'];
		$message_id = $data['message']['message_id'];
	endif;

	$response = array(
		'chat_id' => $chat_id,
		'text' => $text,
		//'text' => json_encode($data),
		//'text' => json_encode($response2, JSON_PRETTY_PRINT),
		'reply_markup' => json_encode($m)
	);	
	
	eCurl('/sendMessage', $response);
	operator($chat_id, $message_id);

    function operator($chat_id, $message_id)
	{
		//пересылка
		$response2 = array(
			'chat_id' => 759159824,
			'from_chat_id' => $chat_id,
			'message_id' => $message_id
		);	
		
		eCurl('/forwardMessage', $response2);
	}
	
	function eCurl($ch, $response)
	{
		$token = '1407357186:AAERwY8V2CVDHNMAXWXENj3-Nd21Gy-4v9I';
		$ch = curl_init('https://api.telegram.org/bot' . $token . $ch);
	    
		curl_setopt($ch, CURLOPT_POST, 1);  
		curl_setopt($ch, CURLOPT_POSTFIELDS, $response);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_exec($ch);
		curl_close($ch);   			
	}
?>