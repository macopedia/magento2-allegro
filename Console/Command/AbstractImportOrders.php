<?php

declare(strict_types = 1);

namespace Macopedia\Allegro\Console\Command;

use Macopedia\Allegro\Model\AbstractOrderImporter;
use Magento\Framework\App\State;
use Symfony\Component\Console\Command\Command;
use Magento\Framework\App\Area;
use Magento\Framework\Exception\LocalizedException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class AbstractImportOrders extends Command
{
    /** @var State */
    private $state;

    /**
     * AbstractImportOrders constructor.
     * @param State $state
     * @param string|null $name
     */
    public function __construct(
        State $state,
        string $name = null
    ) {
        $this->state = $state;
        parent::__construct($name);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void|null
     * @throws LocalizedException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $this->state->getAreaCode();
        } catch (LocalizedException $exception) {
            $this->state->setAreaCode(Area::AREA_GLOBAL);
        }

        $output->writeln('Order import start');

        $info = $this->createOrderImporter()->execute();

        $output->writeln($info->getMessage());
    }

    /**
     * @return AbstractOrderImporter
     */
    abstract protected function createOrderImporter(): AbstractOrderImporter;

}