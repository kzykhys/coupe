<?php

namespace Coupe;

use Bowl\Bowl;
use CoroutineIO\Socket\SocketScheduler;
use Coupe\Http\HttpHandler;
use Coupe\Http\Processor\PhpCgiProcessor;
use Coupe\Http\Server\HttpServer;
use Coupe\Http\Server\SslHttpServer;
use Coupe\Http\SslHttpHandler;
use Coupe\Logger\ConsoleLogger;

/**
 * @author Kazuyuki Hayashi <hayashi@valnur.net>
 */
class Coupe extends Bowl
{

    /**
     * @param array $parameters
     */
    public function __construct($parameters = [])
    {
        $this->parameters = $parameters;

        $this->share('logger', function () {
            return new ConsoleLogger($this['output']);
        });

        $this->share('handler.http', function () {
            $handler = new HttpHandler($this['handler.options']);
            $handler->setLogger($this->get('logger'));

            foreach ($this->getTaggedServices('processor') as $processor) {
                $handler->addProcessor($processor);
            }

            return $handler;
        });

        $this->share('scheduler', function () {
            $scheduler = new SocketScheduler();

            foreach ($this->getTaggedServices('server') as $server) {
                $scheduler->add($server);
            }

            return $scheduler;
        });

        $this->share('server.http', function () {
            $server = new HttpServer($this->get('handler.http'));

            return $server->listen($this['address.http']);
        }, ['server']);

        $this->share('http.processor.php', function () {
            return new PhpCgiProcessor();
        }, ['processor']);
    }

    public function enableSsl()
    {
        $this->share('handler.https', function () {
            $handler = new SslHttpHandler($this['handler.options']);
            $handler->setLogger($this->get('logger'));

            foreach ($this->getTaggedServices('processor') as $processor) {
                $handler->addProcessor($processor);
            }

            return $handler;
        });

        $this->share('server.https', function () {
            $server = new SslHttpServer($this->get('handler.https'));

            return $server->listen($this['address.https']);
        }, ['server']);
    }

} 