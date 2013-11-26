<?php

namespace Coupe\Http\Server;

use CoroutineIO\Server\Server;
use Coupe\Exception\Exception;
use Coupe\Ssl\Certificate;

/**
 * @author Kazuyuki Hayashi <hayashi@valnur.net>
 */
class SslHttpServer extends Server
{

    /**
     * {@inheritdoc}
     */
    public function createSocket($address)
    {
        if (!file_exists($pem = $this->getCachePath($address))) {
            $cert = new Certificate();
            file_put_contents($pem, $cert->createForAddress($address));
        }

        $context = stream_context_create();
        stream_context_set_option($context, 'ssl', 'local_cert', $pem);
        stream_context_set_option($context, 'ssl', 'passphrase', null);
        stream_context_set_option($context, 'ssl', 'allow_self_signed', true);
        stream_context_set_option($context, 'ssl', 'verify_peer', false);

        $socket = @stream_socket_server('tcp://' . $address, $no, $str, STREAM_SERVER_BIND|STREAM_SERVER_LISTEN, $context);

        if (!$socket) {
            throw new Exception("$str ($no)");
        }

        return $socket;
    }

    /**
     * @param $address
     *
     * @return string
     */
    protected function getCachePath($address)
    {
        $fileName = 'coupe_' . md5($address) . '.pem';

        return sys_get_temp_dir() . '/' . $fileName;
    }

}