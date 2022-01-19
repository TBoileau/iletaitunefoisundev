<?php

declare(strict_types=1);

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Process\Process;

final class InstallCommand extends Command
{
    protected static $defaultName = 'app:install';

    public function __construct(private string $projectDir, private string $environment)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Configure your environment.')
            ->setHelp('This command allows you to configure your environment.')
            ->addOption(
                name: 'db-driver',
                mode: InputOption::VALUE_OPTIONAL,
                description: 'Database driver'
            )
            ->addOption(
                name: 'db-user',
                mode: InputOption::VALUE_OPTIONAL,
                description: 'Database user name'
            )
            ->addOption(
                name: 'db-file',
                mode: InputOption::VALUE_OPTIONAL,
                description: 'Database file path'
            )
            ->addOption(
                name: 'db-password',
                mode: InputOption::VALUE_OPTIONAL,
                description: 'Database user password'
            )
            ->addOption(
                name: 'db-name',
                mode: InputOption::VALUE_OPTIONAL,
                description: 'Database name'
            );
    }

    protected function interact(InputInterface $input, OutputInterface $output): void
    {
        $helper = $this->getHelper('question');

        $this->askDatabaseDriver($helper, $input, $output);
        $this->askDatabaseUrl($helper, $input, $output);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->configureEnvironment($input, $output);
        $this->setupDatabase($input, $output);

        return self::SUCCESS;
    }

    private function askDatabaseDriver(
        QuestionHelper $helper,
        InputInterface $input,
        OutputInterface $output
    ): void {
        if (null !== $input->getOption('db-driver')) {
            return;
        }

        $question = new ChoiceQuestion(
            'Please select your the database driver (defaults to mysql)',
            ['mysql' => 'MySQL', 'sqlite' => 'SQLite', 'postgresql' => 'PostgreSQL', 'oci8' => 'Oracle'],
            'mysql'
        );

        $question->setValidator(static function (string $driver) {
            $pdoExtensions = [
                'mysql' => 'pdo_mysql',
                'sqlite' => 'pdo_sqlite',
                'oci8' => 'pdo_oci8',
                'postgresql' => 'pdo_pgsql',
            ];

            if (!extension_loaded($pdoExtensions[$driver])) {
                throw new \RuntimeException(sprintf('You need to install %s extension.', $pdoExtensions[$driver]));
            }
        });

        $question->setErrorMessage('Database driver %s is invalid.');

        $driver = $helper->ask($input, $output, $question);

        $input->setOption('db-driver', $driver);
    }

    private function askDatabaseUrl(
        QuestionHelper $helper,
        InputInterface $input,
        OutputInterface $output
    ): void {
        $driver = $input->getOption('db-driver');

        if ('sqlite' === $driver) {
            if (null !== $input->getOption('db-file')) {
                return;
            }

            $question = new Question('Please provide the relative path of SQLite file : ', 'app/app.db');

            $projectDir = $this->projectDir;

            $question->setAutocompleterCallback(static function (string $path) use ($projectDir): array {
                $inputPath = preg_replace('%(/|^)[^/]*$%', '$1', $path);

                if (($foundFilesAndDirs = scandir($projectDir.'/'.$inputPath)) === false) {
                    $foundFilesAndDirs = [];
                }

                return array_map(static function ($dirOrFile) use ($inputPath): string {
                    return $inputPath.$dirOrFile;
                }, $foundFilesAndDirs);
            });

            $filePath = $helper->ask($input, $output, $question);

            $input->setOption('db-file', $filePath);

            return;
        }

        if (null === $input->getOption('db-user')) {
            $question = new Question('Please provide the database user name (default to root) : ', 'root');

            $userName = $helper->ask($input, $output, $question);

            $input->setOption('db-user', $userName);
        }

        if (null === $input->getOption('db-password')) {
            $question = new Question('Please provide the database user password (default to password) : ', 'password');

            $userPassword = $helper->ask($input, $output, $question);

            $input->setOption('db-password', $userPassword);
        }

        if (null === $input->getOption('db-name')) {
            $question = new Question('Please provide the database name (default to iletaitunefoisundev) : ', 'iletaitunefoisundev');

            $name = $helper->ask($input, $output, $question);

            $input->setOption('db-name', $name);
        }
    }

    private function configureEnvironment(InputInterface $input, OutputInterface $output): void
    {
        $filesystem = new Filesystem();

        $envFilename = sprintf('.env.%s.local', $this->environment);

        $envAbsolutePath = sprintf('%s/%s', $this->projectDir, $envFilename);

        if ($filesystem->exists($envAbsolutePath)) {
            $filesystem->remove($envAbsolutePath);
            $output->writeln(sprintf('<comment>Remove %s</comment>', $envFilename));
        }

        $filesystem->copy(sprintf('%s/.env.dist', $this->projectDir), $envAbsolutePath);
        $output->writeln(sprintf('<info>Create %s</info>', $envFilename));

        /** @var string $content */
        $content = file_get_contents($envAbsolutePath);

        /** @var string $databaseDriver */
        $databaseDriver = $input->getOption('db-driver');

        $content = str_replace(sprintf('#%s ', $databaseDriver), '', $content);

        if ('sqlite' === $databaseDriver) {
            /** @var string $databaseFile */
            $databaseFile = $input->getOption('db-file');
            $content = str_replace('db_file', $databaseFile, $content);
        } else {
            /** @var string $databaseUserName */
            $databaseUserName = $input->getOption('db-user');
            $content = str_replace('db_user', $databaseUserName, $content);

            /** @var string $databaseUserPassword */
            $databaseUserPassword = $input->getOption('db-password');
            $content = str_replace('db_password', $databaseUserPassword, $content);

            /** @var string $databaseName */
            $databaseName = $input->getOption('db-name');
            $content = str_replace('db_name', $databaseName, $content);
        }

        $filesystem->dumpFile($envAbsolutePath, $content);
        $output->writeln('<info>Configure DATABASE_URL env</info>');
    }

    private function setupDatabase(InputInterface $input, OutputInterface $output): void
    {
        $process = new Process([
            'make',
            'database',
            sprintf('env=%s', $this->environment),
        ]);

        $process->run();

        if (!$process->isSuccessful()) {
            $output->writeln(sprintf('<error>%s</error>', $process->getErrorOutput()));

            return;
        }

        $output->writeln('<info>Database created</info>');

        if ('prod' === $this->environment) {
            $output->writeln('<comment>No fixture can be load in prod environment</comment>');

            return;
        }

        $process = new Process([
            'make',
            'fixtures',
            sprintf('env=%s', $this->environment),
        ]);

        $process->run();

        if (!$process->isSuccessful()) {
            $output->writeln(sprintf('<error>%s</error>', $process->getErrorOutput()));

            return;
        }

        $output->writeln('<info>Database fixtures loaded</info>');
    }
}
