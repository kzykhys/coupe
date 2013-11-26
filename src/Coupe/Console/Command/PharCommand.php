<?php

namespace Coupe\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;

/**
 * @author Kazuyuki Hayashi <hayashi@valnur.net>
 */
class PharCommand extends Command
{

    private $fileName = 'coupe.phar';

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('compile:phar')
            ->setDescription('Compiles coupe into a phar archive')
            ->addOption('directory', 'o', InputOption::VALUE_REQUIRED, 'Output directory', 'build')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (ini_get('phar.readonly')) {
            $output->writeln('<info>PHP option "phar.readonly" must be set to 0.</info>');
            $output->writeln('<info>Try `php -d phar.readonly=0 ' . $GLOBALS['argv'][0] . ' compile:phar`</info>');

            return 1;
        }

        if (!is_dir($input->getOption('directory'))) {
            @mkdir($input->getOption('directory'), 0777, true);
        }

        $path = $input->getOption('directory') . DIRECTORY_SEPARATOR . $this->fileName;

        if (file_exists($path)) {
            @unlink($path);
        }

        $output->writeln('Creating Phar...');

        $phar = new \Phar($path, 0, $this->fileName);
        $phar->setSignatureAlgorithm(\Phar::SHA1);
        $phar->startBuffering();

        $finder = Finder::create()
            ->in('.')
            ->files()
            ->name('*.php')
            ->exclude(['test', 'build', 'bin'])
            ->ignoreVCS(true);

        $count = iterator_count($finder);

        /* @var ProgressHelper $progress */
        $progress = $this->getHelper('progress');
        $progress->start($output, $count);

        /* @var \Symfony\Component\Finder\SplFileInfo $file */
        foreach ($finder as $file) {
            $phar->addFile($file, str_replace('\\', '/', $file->getRelativePathname()));
            $progress->advance();
        }

        $progress->finish();

        $script = file_get_contents('bin/coupe');
        $script = preg_replace('/^.*?(<\?php.*)/ms', '\1', $script);
        $phar->addFromString('bin/coupe', $script);

        $phar->setStub($this->getStub());
        $phar->stopBuffering();
        unset($phar);
        chmod($path, 0777);

        $output->writeln('');
        $output->writeln('Build Complete: see ' . $path);

        return 0;
    }

    /**
     * Gets stub
     *
     * @return string
     */
    protected function getStub()
    {
        return "#!/usr/bin/env php\n<?php Phar::mapPhar('coupe.phar'); define('PHAR_RUNNING', true); require 'phar://coupe.phar/bin/coupe'; __HALT_COMPILER();";
    }

}