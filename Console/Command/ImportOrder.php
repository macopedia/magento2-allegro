<?php

declare(strict_types = 1);

namespace Macopedia\Allegro\Console\Command;

use Macopedia\Allegro\Api\CheckoutFormRepositoryInterface;
use Magento\Framework\App\Area;
use Magento\Framework\App\State;
use Magento\Framework\Exception\LocalizedException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Macopedia\Allegro\Model\OrderImporter\Processor\Proxy;

/**
 * ImportOrder command class
 */
class ImportOrder extends Command
{
    const NAME = 'checkoutFormId';

    /** @var Proxy */
    protected $processor;

    /** @var CheckoutFormRepositoryInterface */
    protected $checkoutFormRepository;

    /** @var State */
    protected $state;

    /**
     * ImportOrder constructor.
     * @param Proxy $processor
     * @param CheckoutFormRepositoryInterface $checkoutFormRepository
     * @param State $state
     */
    public function __construct(
        Proxy $processor,
        CheckoutFormRepositoryInterface $checkoutFormRepository,
        State $state
    ) {
        parent::__construct();
        $this->processor = $processor;
        $this->checkoutFormRepository = $checkoutFormRepository;
        $this->state = $state;
    }

    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this->setName('macopedia:allegro:order-import');
        $this->setDescription("Import order from Allegro account by checkout form id");
        $this->setDefinition(
            [
                new InputOption(
                    self::NAME,
                    'c',
                    InputOption::VALUE_REQUIRED,
                    'Enter the ID of the order you want to import'
                )
            ]
        );
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     * @throws LocalizedException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $this->state->getAreaCode();
        } catch (LocalizedException $exception) {
            $this->state->setAreaCode(Area::AREA_GLOBAL);
        }

        $checkoutFormId = $input->getOption(self::NAME);
        if (!$checkoutFormId) {
            throw new LocalizedException(__('Please enter the ID of the order you want to import'));
        }

        try {
            $checkoutForm = $this->checkoutFormRepository->get($checkoutFormId);
        } catch (\Exception $e) {
            $output->writeln("Error while requesting checkout form with id '{$checkoutFormId}'");
            return;
        }

        try {
            $this->processor->processOrder($checkoutForm);
        } catch (\Exception $e) {
            $output->writeln("Error while creating order with id '{$checkoutFormId}'");
            $output->writeln($e->getMessage());
            return;
        }

        $output->writeln("Order with id '{$checkoutFormId}' has been successfully created");
    }
}
