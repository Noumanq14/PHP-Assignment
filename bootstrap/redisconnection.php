<?php
namespace redisConnection;
use Predis\Client;

class redisconnection
{
    public function connection()
    {
        return new Client([
            'scheme' => 'tcp',
            'host' => '127.0.0.1',
            'port' => 32768,
        ]);
    }
}