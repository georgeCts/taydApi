<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Pusher\Pusher;

class ChannelsController extends Controller
{
    private $pusher;

    public function __construct() {
        $config = Config::get('broadcasting.connections.pusher');

        $options = [
            'cluster'   => $config['options']['cluster'],
            'encrypted' => $config['options']['encrypted']
        ];

        $this->pusher = new Pusher(
            $config['key'],
            $config['secret'],
            $config['app_id'],
            $options
        );
    }

    public function auth(Request $request) {
        echo $this->pusher->socket_auth($request->channel_name, $request->socket_id);
    }
}
