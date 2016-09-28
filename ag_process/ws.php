<?php

$cesta = "I:/xamp/htdocs/armygame/www/";


include $cesta."config.php";


include $cesta."inc/class.php";
include $cesta."inc/data.php";
$db = new db($cfg["mysqlserver"],$cfg["mysqluser"],$cfg["mysqlpw"],$cfg["mysqldb"]);

$host = 'localhost'; //host
$port = '9000'; //port
$null = NULL; //null var

function auth($hash){
    global $db;
    
    $db->query("SELECT * FROM `ws_auth` WHERE `hash` = %s",[$hash]);

    if($db->data){
        
        $db->query("DELETE FROM `ws_auth` WHERE `id` = %s",[$db->data[0]["id"]],false);
        return $db->data[0]["user"];
    }else{
        return false;
    }
}

function napoj($id,$socket){
    global $users, $s_users;
    $users[$id][] = $socket;
    $s_users[$socket] = $id;
}

function odpoj($socket){
    global $users, $s_users;
    if(isset($s_users[$socket])){
        $id = $s_users[$socket];
        unset($s_users[$socket]);
        $s = array_search($socket, $users[$id]);
        unset($s_users[$id][$s]);
        if(isset($s_users[$id]) && !$s_users[$id]){
            unset($s_users[$id]);
        }
    }
}

$users = [];
$s_users = [];
$sv = [];
$chat = new chat();

//Create TCP/IP sream socket
$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
//reuseable port
socket_set_option($socket, SOL_SOCKET, SO_REUSEADDR, 1);

//bind socket to specified host
socket_bind($socket, 0, $port);

//listen to port
socket_listen($socket);

//create & add listning socket to the list
$clients = array($socket);

//start endless loop, so that our script doesn't stop
while (true) {
	//manage multipal connections
	$changed = $clients;
	//returns the socket resources in $changed array
	socket_select($changed, $null, $null, 0, 10);
	
	//check for new socket
	if (in_array($socket, $changed)) {
		$socket_new = socket_accept($socket); //accpet new socket
		$clients[] = $socket_new; //add socket to client array
		
		$header = socket_read($socket_new, 1024); //read data sent by the socket
		perform_handshaking($header, $socket_new, $host, $port); //perform websocket handshake
		
		socket_getpeername($socket_new, $ip); //get ip address of connected socket
		
		//make room for new socket
		$found_socket = array_search($socket, $changed);
		unset($changed[$found_socket]);
	}
	
	//loop through all connected sockets
	foreach ($changed as $changed_socket) {	
		
		//check for any incomming data
		while(@socket_recv($changed_socket, $buf, 1024, 0) >= 1)
		{
			$received_text = unmask($buf); //unmask data
                        echo $received_text."\n";
			if($rec = json_decode($received_text,true)){
                            
                            if(isset($s_users[$changed_socket])){
                                if(isset($rec["typ"])){
                                    if($rec["typ"] == "chat" && isset($rec["pro"]) && isset($rec["text"])){
                                        //echo $rec["text"]."\n";
                                        send_message_user($s_users[$changed_socket], [
                                            "typ" => "chatme",
                                            "pro" => intval($rec["pro"]),
                                            "text" => $rec["text"],
                                            "time" => date("d.m.Y H:i:s",time())
                                        ]);
                                        send_message_user($rec["pro"], [
                                            "typ" => "chat",
                                            "od" => intval($s_users[$changed_socket]),
                                            "text" => $rec["text"],
                                            "time" => date("d.m.Y H:i:s",time())
                                        ]);
                                        $chat->pridej($s_users[$changed_socket], $rec["pro"], $rec["text"]);
                                    }
                                }
                            }else if(in_array($changed_socket, $sv)){
                                if($rec["typ"] == "mapa_refresh" && isset($rec["bloky"])){
                                    send_message_all($rec);
                                }
                            }else{
                                if(isset($rec["typ"])){
                                    if($rec["typ"] == "sv_auth" && isset($rec["hash"]) && $rec["hash"] == $cfg["wsauth"]){
                                        $sv[] = $changed_socket;
                                    }
                                    else if($rec["typ"] == "auth" && isset($rec["hash"])){
                                        if($id = auth($rec["hash"])){
                                            napoj($id,$changed_socket);
                                            send_message_socket($changed_socket,["zdar","2"]);
                                        }
                                    } 
                                }
                            }
                        }
			break 2; //exist this loop
		}
		
		$buf = @socket_read($changed_socket, 1024, PHP_NORMAL_READ);
		if ($buf === false) { // check disconnected client
			// remove client for $clients array
			$found_socket = array_search($changed_socket, $clients);
			socket_getpeername($changed_socket, $ip);
			unset($clients[$found_socket]);
			odpoj($changed_socket);
                        
			//notify all users about disconnected connection
			$response = mask(json_encode(array('type'=>'system', 'message'=>$ip.' disconnected')));
			send_message($response);
		}

	}
	//sleep(1);
}
// close the listening socket
socket_close($sock);

function send_message($msg)
{
	global $clients;
	foreach($clients as $changed_socket)
	{
		@socket_write($changed_socket,$msg,strlen($msg));
	}
	return true;
}

function send_message_socket($socket,$arr)
{
    $msg = mask(json_encode($arr));
    @socket_write($socket,$msg,strlen($msg));
}

function send_message_user($user,$arr)
{
    global $users;
    if(isset($users[$user])){
        foreach($users[$user] as $socket){
            send_message_socket($socket, $arr);
        }
    }
}

function send_message_all($arr)
{
    global $users;
    foreach($users as $us){
        foreach($us as $socket){
            send_message_socket($socket, $arr);
        }
    }
}


//Unmask incoming framed message
function unmask($text) {
	$length = ord($text[1]) & 127;
	if($length == 126) {
		$masks = substr($text, 4, 4);
		$data = substr($text, 8);
	}
	elseif($length == 127) {
		$masks = substr($text, 10, 4);
		$data = substr($text, 14);
	}
	else {
		$masks = substr($text, 2, 4);
		$data = substr($text, 6);
	}
	$text = "";
	for ($i = 0; $i < strlen($data); ++$i) {
		$text .= $data[$i] ^ $masks[$i%4];
	}
	return $text;
}

//Encode message for transfer to client.
function mask($text){
	$b1 = 0x80 | (0x1 & 0x0f);
	$length = strlen($text);
	if($length <= 125)
		$header = pack('CC', $b1, $length);
	elseif($length > 125 && $length < 65536)
		$header = pack('CCn', $b1, 126, $length);
	elseif($length >= 65536)
		$header = pack('CCNN', $b1, 127, $length);
	return $header.$text;
}

//handshake new client.
function perform_handshaking($receved_header,$client_conn, $host, $port)
{
	$headers = array();
	$lines = preg_split("/\r\n/", $receved_header);
	foreach($lines as $line)
	{
		$line = chop($line);
		if(preg_match('/\A(\S+): (.*)\z/', $line, $matches)){
			$headers[$matches[1]] = $matches[2];
		}
	}
        if(1){
            $secKey = $headers['Sec-WebSocket-Key'];
            $secAccept = base64_encode(pack('H*', sha1($secKey . '258EAFA5-E914-47DA-95CA-C5AB0DC85B11')));

            $upgrade  = "HTTP/1.1 101 Web Socket Protocol Handshake\r\n" .
            "Upgrade: websocket\r\n" .
            "Connection: Upgrade\r\n" .
            "WebSocket-Origin: $host\r\n" .
            "WebSocket-Location: ws://$host:$port/\r\n".
            "Sec-WebSocket-Accept:$secAccept\r\n\r\n";
            socket_write($client_conn,$upgrade,strlen($upgrade));
        }
}
