<?php

namespace Coupe\Console\Command;

use Coupe\Coupe;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Kazuyuki Hayashi <hayashi@valnur.net>
 */
class StartCommand extends Command
{

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('start')
            ->setDescription('Start the server')
            ->addArgument('address', InputArgument::OPTIONAL, '<host>:<port>', 'localhost:8080')
            ->addOption('docroot', 't', InputOption::VALUE_REQUIRED, 'Specify document root')
            ->addOption('with-ssl', 's', InputOption::VALUE_OPTIONAL, '<host>:<port>', 'localhost:8443')
            ->addOption('without-ssl', null, InputOption::VALUE_NONE, 'Disables ssl transport')
            ->addOption('fallback', null, InputOption::VALUE_REQUIRED, 'Fallback script', false)
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (($docRoot = $input->getOption('docroot')) && is_dir($docRoot)) {
            chdir($docRoot);
        }

        $coupe = new Coupe([
            'output' => $output,
            'handler.options' => [
                'fallback' => $input->getOption('fallback')
            ],
            'address.http' => $input->getArgument('address')
        ]);

        $output->writeln('<bg=cyan;fg=black>Coupé HTTP Server (dev-master)</bg=cyan;fg=black>');
        $output->writeln('Started at ' . date('r'));
        $output->writeln('Document root is ' . getcwd());
        $output->writeln('Listening on http://' . $input->getArgument('address'));

        if (!$input->getOption('without-ssl')) {
            $coupe['address.https'] = $input->getOption('with-ssl');
            $coupe->enableSsl();
            $output->writeln('Listening on https://' . $input->getOption('with-ssl'));
        }

        $output->writeln('Press Ctrl-C to quit');
        $output->writeln('');

        $coupe->get('scheduler')->run();
    }

} 