<?php

namespace Coupe\Console\Command\Traits;

use Symfony\Component\Process\Process;

trait VersionTrait
{

    /**
     * @return string
     */
    protected function getVersionFromGit()
    {
        $process = new Process('git describe --tags HEAD');
        $process->run();

        if ($process->isSuccessful()) {
            return $process->getOutput();
        }

        $process = new Process('git log -1 --pretty="%H" HEAD');
        $process->run();

        if ($process->isSuccessful()) {
            $version = $process->getOutput();
            $version = substr($version, 0, 8);

            return 'dev-master(' . $version . ')';
        }

        return 'dev-master';
    }

} 