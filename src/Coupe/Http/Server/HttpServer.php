<?php

namespace Coupe\Http\Server;

use CoroutineIO\Server\Server;
use Coupe\Exception\Exception;

/**
 * @author Kazuyuki Hayashi <hayashi@valnur.net>
 */
class HttpServer extends Server
{

    /**
     * {@inheritdoc}
     */
    public function createSocket($address)
    {
        $socket = @stream_socket_server('tcp://' . $address, $no, $str);

        if (!$socket) {
            throw new Exception("$str ($no)");
        }

        return $socket;
    }

}