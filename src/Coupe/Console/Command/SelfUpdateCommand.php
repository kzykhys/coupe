<?php

namespace Coupe\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

class SelfUpdateCommand extends Command
{

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('self-update')
            ->setDescription('Updates coupe.phar to the latest version')
            ->addOption('force', 'f', InputOption::VALUE_NONE, 'Force update to the latest version')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $current = $this->getApplication()->getVersion();

        if (false === ($latest = @file_get_contents('http://kzykhys.com/coupe/version'))) {
            $output->writeln('<error>Failed to connect to http://kzykhys.com/coupe/version</error>');

            return 255;
        }

        if (!$input->getOption('force')) {
            if ($current === $latest) {
                $output->writeln('<info>You are using the latest version</info>');

                return 0;
            }
        }

        /* @var \Symfony\Component\Console\Helper\ProgressHelper $progress */
        $progress = $this->getHelper('progress');
        $fileSize = 0;
        $currentPercent = 0;

        $progress->start($output, 100);

        $context = stream_context_create();
        stream_context_set_params(
            $context,
            [
                "notification" => function ($c, $s, $m, $mc, $transferred, $max) use (
                        &$progress,
                        &$fileSize,
                        &$currentPercent
                    ) {
                        switch ($c) {
                            case STREAM_NOTIFY_FILE_SIZE_IS:
                                $fileSize = $max;
                                break;
                            case STREAM_NOTIFY_PROGRESS:
                                if ($transferred > 0) {
                                    $percent = (int) ($transferred / $fileSize * 100);
                                    $progress->advance($percent - $currentPercent);
                                    $currentPercent = $percent;
                                }
                                break;
                        }
                    }
            ]
        );

        $output->writeln('Downloading <fg=green;options=bold>' . $latest . '</fg=green;options=bold> ...');

        if (false === ($phar = @file_get_contents('http://kzykhys.com/coupe/coupe.phar', false, $context))) {
            $output->writeln('<error>Failed to download new coupe version</error>');

            return 255;
        }

        $progress->setCurrent(100);
        $progress->finish();

        $pharPath = $GLOBALS['argv'][0];

        $fs = new Filesystem();
        $fs->copy($pharPath, $backup = tempnam(sys_get_temp_dir(), 'coupe_phar_backup'));

        if (false == @file_put_contents($pharPath, $phar)) {
            $fs->remove($pharPath);
            $fs->rename($backup, $pharPath);
            $output->writeln('<error>Failed to update coupe to the latest version</error>');

            return 1;
        }

        $fs->remove($backup);

        return 0;
    }

} 