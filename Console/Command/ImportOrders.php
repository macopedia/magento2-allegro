<?php

declare(strict_types = 1);

namespace Macopedia\Allegro\Console\Command;

use Macopedia\Allegro\Model\AbstractOrderImporter;
use Macopedia\Allegro\Model\OrderImporterFactory;
use Magento\Framework\App\State;

/**
 * ImportOrders command class
 */
class ImportOrders extends AbstractImportOrders
{

    /** @var OrderImporterFactory */
    protected $orderImporterFactory;

    /**
     * ImportOrders constructor.
     * @param OrderImporterFactory $orderImporterFactory
     * @param State $state
     */
    public function __construct(
        OrderImporterFactory $orderImporterFactory,
        State $state
    ) {
        $this->orderImporterFactory = $orderImporterFactory;
        parent::__construct($state);
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('macopedia:allegro:orders-import');
        $this->setDescription("Import orders from Allegro account ");
    }

    /**
     * @return AbstractOrderImporter
     */
    protected function createOrderImporter(): AbstractOrderImporter
    {
        return $this->orderImporterFactory->create();
    }
}
