<?php

namespace Macopedia\Allegro\Console\Command;

use Macopedia\Allegro\Logger\Logger;
use Macopedia\Allegro\Model\OrderImporter;
use Magento\Framework\App\Area;
use Magento\Framework\App\State;
use Magento\Framework\Exception\LocalizedException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * OrderImport command class
 */
class ImportOrders extends Command
{
    const COMMAND_NAME = "macopedia:allegro:orders-import";

    /** @var OrderImporter */
    private $orderImporter;

    /** @var State */
    private $state;

    /**
     * ImportOrders constructor.
     * @param Logger $logger
     * @param OrderImporter $orderImporter
     * @param State $state
     * @param null $name
     */
    public function __construct(
        Logger $logger,
        OrderImporter $orderImporter,
        State $state,
        $name = null
    ) {
        $this->logger = $logger;
        $this->orderImporter = $orderImporter;
        $this->state = $state;
        parent::__construct($name);
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $this->state->getAreaCode();
        } catch (LocalizedException $exception) {
            $this->state->setAreaCode(Area::AREA_GLOBAL);
        }

        $output->writeln('Order import start');
        $info = $this->orderImporter->execute();
        $output->writeln($info->getMessage());
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName(self::COMMAND_NAME);
        $this->setDescription("Import orders from Allegro account ");
        parent::configure();
    }
}
