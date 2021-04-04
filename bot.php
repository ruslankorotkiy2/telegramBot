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
		//реакция на нажатие кнопки
		$chat_id = $data['callback_query']['message']['chat']['id'];

		switch ($data['callback_query'][data]) 
		{
			case 1: $m = $m2; break;
			case 4: $m = $m3; break;
        }

		$message_id = $data['callback_query']['message']['message_id'];
		
	else:
		$chat_id = $data['message']['chat']['id'];
		$message_id = $data['message']['message_id'];
	endif;

	$response = array(
		'chat_id' => $chat_id,
		'reply_markup' => json_encode($m)
	);	
	
	eCurl('/sendMessage', $response);
	operator($chat_id, $message_id);
		
	//функция пересылки сообщения оператору
    function operator($chat_id, $message_id)
	{
		$response2 = array(
			'chat_id' => 777,
			'from_chat_id' => $chat_id,
			'message_id' => $message_id
		);	
		
		eCurl('/forwardMessage', $response2);
	}
	
	//функция выполняющая запрос
	function eCurl($ch, $response)
	{
		$token = '777';
		$ch = curl_init('https://api.telegram.org/bot' . $token . $ch);
	    
		curl_setopt($ch, CURLOPT_POST, 1);  
		curl_setopt($ch, CURLOPT_POSTFIELDS, $response);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_exec($ch);
		curl_close($ch);   			
	}
?>