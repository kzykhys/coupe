<?php

namespace Coupe\Http;

/**
 * @author Kazuyuki Hayashi <hayashi@valnur.net>
 */
interface ProcessorInterface
{

    /**
     * @param \SplFileInfo $path
     * @param Request      $request
     * @param array        $env
     *
     * @return Response
     */
    public function execute(\SplFileInfo $path, Request $request, array $env = []);

    /**
     * @param \SplFileInfo $path
     *
     * @return boolean
     */
    public function isSupported(\SplFileInfo $path);

} 