<?php

declare(strict_types=1);

namespace App\Shared\Command;

use Laudis\Neo4j\Contracts\ClientInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class Neo4jDeleteNodesCommand extends Command
{
    protected static $defaultName = 'app:neo4j:delete-nodes';

    public function __construct(private ClientInterface $client)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->client->run('MATCH (n) DETACH DELETE n;');

        return Command::SUCCESS;
    }
}
