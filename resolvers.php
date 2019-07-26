<?php 

	$client = new MongoDB\Client(
		'mongodb://localhost:27017'
	);

	$db = $client->graphql;
	$roomCollection = $db->rooms;
	$messageCollection = $db->messages;

	$roomByName = function($name){
		global $roomCollection;
		return $roomCollection->findOne(
			['name' => $name]
		);
	};

	$roomType = [
		'messages' => function($room){
			global $messageCollection;
			$messagesList = $messageCollection->find(
				['roomId' => $room['id']]
			);
			$list = array();
			foreach($messagesList as $message){
				$oneMessage = ["id"=>$message['_id'], "roomId"=>$message['roomId'], "body"=>$message['body'], 'timestamp'=>$message['timestamp']];
				array_push($list, $oneMessage);
			}
			return $list;
		}
	];

	$queryType = [
		'rooms' => function(){
			global $roomCollection;
			global $messageCollection;
			$roomList = $roomCollection->find();
			$listRoom = array();
			foreach($roomList as $room){
				$oneRoom = ["id"=> $room['_id'] ,"name"=>$room['name']];
				array_push($listRoom, $oneRoom);
			};
			return $listRoom;
		},
		'messages' => function($root, $args) use ($roomByName){
			global $messageCollection;
			$roomName = $args['roomName'];
			$room = $roomByName($roomName);
			$messagesList = $messageCollection->find(
				['roomId' => $room['_id']]
			);
			$list = array();
			foreach($messagesList as $message){
				$oneMessage = ["id"=>$message['_id'], "roomId"=>$message['roomId'], "body"=>$message['body'], 'timestamp'=>$message['timestamp']];
				array_push($list, $oneMessage);
			}
			return $list;
		},
	];

	$mutationType = [
		'start' => function($root, $args){
			global $roomCollection;
			$roomName = $args['roomName'];
			$room = ['name' => $roomName];
			$result = $roomCollection->insertOne($room);
			$room['id'] = $result->getInsertedId();
			return $room;
		},
		'chat' => function($root, $args) use ($roomByName){
			global $messageCollection;
			$roomName = $args['roomName'];
			$body = $args['body'];
			$room = $roomByName($roomName);
			$message = ['roomId'=>$room['_id'], 'body'=>$body, 'timestamp'=>date('m/d/Y h:i:s a', time())];
			$result = $messageCollection->insertOne($message);
			$message['id'] = $result->getInsertedId();
			return $message;
		}
	];

	return [
    'Room'     => $roomType,
    'Query'    => $queryType,
    'Mutation' => $mutationType,
	];