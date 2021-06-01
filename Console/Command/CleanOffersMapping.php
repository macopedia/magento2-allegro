<?php

declare(strict_types=1);

namespace Macopedia\Allegro\Console\Command;

use Macopedia\Allegro\Model\OffersMapping;
use Magento\Framework\App\Area;
use Magento\Framework\App\State;
use Magento\Framework\Exception\LocalizedException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * CleanOffersMapping command class
 */
class CleanOffersMapping extends Command
{
    /** @var State */
    protected $state;

    /** @var OffersMapping */
    protected $offersMapping;

    /**
     * CleanOffersMapping constructor.
     * @param State $state
     * @param OffersMapping $offersMapping
     */
    public function __construct(
        State $state,
        OffersMapping $offersMapping
    ) {
        parent::__construct();
        $this->state = $state;
        $this->offersMapping = $offersMapping;
    }

    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this->setName('macopedia:allegro:clean-offers-mapping');
        $this->setDescription("Clean old offers mapping");
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

        try {
            $this->offersMapping->clean();
        } catch (\Exception $e) {
            $output->writeln('Error occurred while trying to clean old offers mapping');
            $output->writeln($e->getMessage());
        }
    }
}
