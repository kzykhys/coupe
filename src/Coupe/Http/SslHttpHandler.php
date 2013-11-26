<?php

namespace Coupe\Http;

use CoroutineIO\Socket\ProtectedStreamSocket;
use Coupe\Exception\Exception;
use CoroutineIO\Socket\StreamSocket;

/**
 * @author Kazuyuki Hayashi <hayashi@valnur.net>
 */
class SslHttpHandler extends HttpHandler
{

    /**
     * {@inheritdoc}
     */
    protected function prepareSocket(StreamSocket $socket)
    {
        $socket->block(true);

        if (false === stream_socket_enable_crypto($socket->getRaw(), true, STREAM_CRYPTO_METHOD_SSLv23_SERVER)) {
            throw new Exception('SSL negotiation failed');
        }

        $socket->block(false);
    }

    /**
     * @param Request               $request
     * @param ProtectedStreamSocket $socket
     *
     * @return Response|void
     */
    protected function handleRequest(Request $request, ProtectedStreamSocket $socket)
    {
        $request->setHeader('Https', 1);

        return parent::handleRequest($request, $socket);
    }

}