<?php

namespace App\Http\Controllers;

use App\Models\User;
use BeyondCode\LaravelWebSockets\Apps\App;
use BeyondCode\LaravelWebSockets\Dashboard\DashboardLogger;
use BeyondCode\LaravelWebSockets\Facades\StatisticsLogger;
use BeyondCode\LaravelWebSockets\WebSockets\Channels\ChannelManager;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Psr\Http\Message\RequestInterface;
use Ratchet\ConnectionInterface;
use Ratchet\RFC6455\Messaging\MessageInterface;
use Ratchet\WebSocket\MessageComponentInterface;

class MyCustomWebSocketHandler implements MessageComponentInterface
{
    /** @var string */
    protected $requestBuffer = '';

    /** @var RequestInterface */
    protected $request;

    /** @var int */
    protected $contentLength;

    /** @var \BeyondCode\LaravelWebSockets\WebSockets\Channels\ChannelManager */
    protected $channelManager;

    public function __construct(ChannelManager $channelManager)
    {
        $this->channelManager = $channelManager;
    }

    public function onOpen(ConnectionInterface $connection)
    {
        // TODO: Implement onOpen() method.

        $connection->app = App::findById(env('PUSHER_APP_ID'));
        $this->generateSocketId($connection);
        $this->establishConnection($connection);

        return $connection;

    }

    public function onClose(ConnectionInterface $connection)
    {
        echo "samji close";
        // TODO: Implement onClose() method.
    }

    public function onError(ConnectionInterface $connection, \Exception $e)
    {
        echo "samji error";
        // TODO: Implement onError() method.
    }

    public function onMessage(ConnectionInterface $connection, MessageInterface $msg)
    {
        // TODO: Implement onMessage() method.
        echo "There is a message";
        $dmsg = json_decode($msg);
        echo $dmsg->event;

        Log::info("There is a message");
        Log::info($dmsg);

        if ($dmsg->event == "contact-verify") {
            $this->contactVerify($connection, $dmsg->data->phones);
        }

        return "Message";
    }

    protected function findContentLength(array $headers): int
    {
        return Collection::make($headers)->first(function ($values, $header) {
                return strtolower($header) === 'content-length';
            })[0] ?? 0;
    }

    protected function generateSocketId(ConnectionInterface $connection)
    {
        $socketId = sprintf('%d.%d', random_int(1, 1000000000), random_int(1, 1000000000));

        $connection->socketId = $socketId;

        return $this;
    }

    protected function establishConnection(ConnectionInterface $connection)
    {
        $connection->send(json_encode([
            'event' => 'pusher:connection_established',
            'data' => json_encode([
                'socket_id' => $connection->socketId,
                'activity_timeout' => 30,
            ]),
        ]));

        DashboardLogger::connection($connection);

        StatisticsLogger::connection($connection);

        return $this;
    }

    protected function contactVerify(ConnectionInterface $connection, $phones)
    {

        $pha = explode(",", $phones);

        foreach ($pha as $phone) {
            $user = User::where("phone", trim($phone))->first();

            if ($user) {
                $datam["email"] = $user->email;
                $datam["phone"] = $phone;
                $datam["name"] = $user->lastname . " " . $user->firstname;
                $data[] = $datam;
            }

        }

        Log::info("Contact verify response");
        Log::info(json_encode($data));

        $connection->send(json_encode([
            'event' => 'contact-verify',
            'message' => 'Validated Successfully',
            'data' => !empty($data) ? $data : [],
        ]));

        return $this;
    }

}
