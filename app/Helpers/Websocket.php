<?php

namespace App\Helpers;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;


class Websocket implements MessageComponentInterface {
    protected $clients;

    protected $rooms;
    protected $users;
    protected $users_name;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn) {
        // Store the new connection to send messages to later
        $this->clients->attach($conn);

        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $msg = json_decode($msg);
        if($msg->message == 'new room'){
            $this->rooms[$msg->value][$from->resourceId] = $from;
            $this->users[$from->resourceId] = $msg->value;
            $this->users_name[$msg->value][$from->resourceId] = $msg->user;
            $users = [];
            foreach ($this->users_name[$msg->value] as $user){
                $users[] = $user;
            }
            $messaage = ['message' => 'connect', 'users' => $users];
            foreach ($this->rooms[$msg->value] as $client){
                    $client->send(json_encode($messaage));
            }
            dump($this->users);
        }
        elseif ($msg->message == 'new message'){
            $room = $this->users[$from->resourceId];
            foreach ($this->rooms[$room] as $client){
                $messaage = ['message' => 'message', 'value' => $msg->value, 'user' => $this->users_name[$room][$from->resourceId]];
                $client->send(json_encode($messaage));
            }
        }
        elseif($msg->message == 'new order'){
            $room = $this->users[$from->resourceId];
            foreach ($this->rooms[$room] as $client){
                if ($from !== $client) {
                    $client->send(json_encode($msg->value));
                }
                else{
                    $client->send(true);
                }
            }
        }

//        foreach ($this->clients as $client) {
//            if ($from !== $client) {
//                // The sender is not the receiver, send to each client connected
//                $client->send($msg);
//            }
//        }
    }

    public function onClose(ConnectionInterface $conn) {
        // The connection is closed, remove it, as we can no longer send it messages
        $this->clients->detach($conn);
        $room = $this->users[$conn->resourceId];
        unset($this->rooms[$room][$conn->resourceId]);
        unset($this->users[$conn->resourceId]);
        unset($this->users_name[$room][$conn->resourceId]);
        $users = [];
        foreach ($this->users_name[$room] as $user){
            $users[] = $user;
        }
        $messaage = ['message' => 'connect', 'users' => $users];
        foreach ($this->rooms[$room] as $client){
            $client->send(json_encode($messaage));
        }
        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }
}
