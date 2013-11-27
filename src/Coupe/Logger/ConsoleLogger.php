<?php

namespace Coupe\Logger;

use Psr\Log\AbstractLogger;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Kazuyuki Hayashi <hayashi@valnur.net>
 */
class ConsoleLogger extends AbstractLogger
{

    /**
     * @var \Symfony\Component\Console\Output\OutputInterface
     */
    private $output;

    /**
     * @param OutputInterface $output
     */
    public function __construct(OutputInterface $output)
    {
        $this->output = $output;
    }

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed  $level
     * @param string $message
     * @param array  $context
     *
     * @return null
     */
    public function log($level, $message, array $context = [])
    {
        if ($level == -1) {
            $this->output->writeln(sprintf('<bg=red;fg=white>%s</bg=red;fg=white>', trim($message)));

            return;
        } elseif ($level < 300) {
            $format = '<fg=white;bg=green>%s</fg=white;bg=green> ';
        } elseif ($level < 400) {
            $format = '%s ';
        } elseif ($level < 500) {
            $format = '<fg=red>%s</fg=red> ';
        } elseif ($level >= 500) {
            $format = '<fg=white;bg=red>%s</fg=white;bg=red> ';
        }

        $this->output->writeln('[' . date('Y/m/d H:i:s') . '] ' . sprintf($format, $level) . trim($message));
    }

}