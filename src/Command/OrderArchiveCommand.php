<?php

namespace App\Command;

use App\Database\Adaptor;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class OrderArchiveCommand extends Command
{
    protected static $defaultName = 'order:archive';

    private $dbAdaptor;

    public function __construct(Adaptor $adaptor)
    {
        $this->dbAdaptor = $adaptor;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setDescription('Archives orders');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $now = date("D M d, Y G:i");
        $output->writeln(sprintf("Order archiving process started at %s", $now));
        try {
            $this->dbAdaptor->archiveOrders();
        } catch (\Exception $e) {
            $output->writeln("Order archiving process ended with an error: " . $e->getMessage());
        }
        $output->writeln("Order archiving process ended successfully!");
    }
}