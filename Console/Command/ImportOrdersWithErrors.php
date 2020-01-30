<?php

declare(strict_types = 1);

namespace Macopedia\Allegro\Console\Command;

use Macopedia\Allegro\Model\AbstractOrderImporter;
use Macopedia\Allegro\Model\OrderWithErrorImporterFactory;
use Magento\Framework\App\State;

/**
 * ImportOrdersWithErrors command class
 */
class ImportOrdersWithErrors extends AbstractImportOrders
{

    /** @var OrderWithErrorImporterFactory */
    private $orderImporterFactory;

    /**
     * ImportOrdersWithErrors constructor.
     * @param OrderWithErrorImporterFactory $orderImporterFactory
     * @param State $state
     * @param null $name
     */
    public function __construct(
        OrderWithErrorImporterFactory $orderImporterFactory,
        State $state,
        $name = null
    ) {
        $this->orderImporterFactory = $orderImporterFactory;
        parent::__construct($state, $name);
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('macopedia:allegro:orders-with-errors-import');
        $this->setDescription("Import orders with errors from Allegro account");
    }

    /**
     * @return AbstractOrderImporter
     */
    protected function createOrderImporter(): AbstractOrderImporter
    {
        return $this->orderImporterFactory->create();
    }
}
