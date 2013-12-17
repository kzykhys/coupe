<?php

namespace Coupe\Console\Command;

use Coupe\Console\Command\Traits\VersionTrait;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Process\Process;

/**
 * @author Kazuyuki Hayashi <hayashi@valnur.net>
 */
class ReleaseCommand extends Command
{

    use VersionTrait;

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('release')
            ->setDescription('Releases phar package (Requires admin privileges to kzykhys/coupe repo)')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $fs = new Filesystem();

        $version = $this->getVersionFromGit();

        $output->writeln('Release Coupe ' . $version);

        $output->writeln('Compiling phar ...');
        if (0 != $this->compile($output)) {
            return 255;
        }

        chdir('build');

        $output->writeln('Cloning repository ...');
        $process = new Process('git clone git@github.com:kzykhys/coupe.git');
        $process->run();
        if (!$process->isSuccessful()) {
            $output->writeln('<error>Failed to clone git repository</error>');
            return 9;
        }
        chdir('coupe');

        $process = new Process('git fetch');
        $process->run();
        $process = new Process('git checkout -b gh-pages origin/gh-pages');
        $process->run();
        if (!$process->isSuccessful()) {
            $output->writeln('<error>Failed to checkout branch "gh-pages"</error>');
            return 5;
        }

        $fs->copy('../coupe.phar', 'coupe.phar', true);
        $fs->copy('../../bin/install', 'install', true);
        file_put_contents('version', $version);

        $output->writeln('Staging...');
        $process = new Process('git add coupe.phar version install');
        $process->run();

        $process = new Process('git status');
        $process->run();
        $output->writeln($process->getOutput());

        /* @var \Symfony\Component\Console\Helper\DialogHelper $dialog */
        $dialog = $this->getHelper('dialog');

        if ($result = $dialog->askConfirmation($output, 'Do you release ' . $version . '? [yes/no]: ', false)) {
            $process = new Process('git commit -m "Releases coupe ' . $version . '"');
            $process->run();
            $output->writeln($process->getOutput());

            $process = new Process('git push origin gh-pages');
            $process->run();
            $output->writeln($process->getOutput());
        }

        chdir('../');
        $fs->remove('coupe/');

        return 0;
    }

    protected function compile(OutputInterface $output)
    {
        $command = $this->getApplication()->find('compile:phar');
        $subInput = new ArrayInput([
            'command' => 'compile:phar'
        ]);

        return $command->run($subInput, $output);
    }

} 