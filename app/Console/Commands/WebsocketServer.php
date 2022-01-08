<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Ratchet;
use App\Helpers\Websocket;

class WebsocketServer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'server:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'websocket server';

    protected $worker;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
       // $this->worker = new Worker('tcp://192.168.10.100:2346');
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $server = Ratchet\Server\IoServer::factory(
            new Ratchet\Http\HttpServer(
                new Ratchet\WebSocket\WsServer(
                    new Websocket()
                )
            ),
            8095
        );

        $server->run();
    }
}
