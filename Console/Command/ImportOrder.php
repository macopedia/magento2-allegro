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
use Macopedia\Allegro\Model\OrderImporter\Processor;

/**
 * ImportOrder command class
 */
class ImportOrder extends Command
{
    const NAME = 'checkoutFormId';

    /** @var Processor */
    private $processor;

    /** @var CheckoutFormRepositoryInterface */
    private $checkoutFormRepository;

    /** @var State */
    private $state;

    /**
     * ImportOrder constructor.
     * @param Processor $processor
     * @param CheckoutFormRepositoryInterface $checkoutFormRepository
     * @param State $state
     * @param string|null $name
     */
    public function __construct(
        Processor $processor,
        CheckoutFormRepositoryInterface $checkoutFormRepository,
        State $state,
        string $name = null
    ) {
        parent::__construct($name);
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
