<?php

namespace Macopedia\Allegro\Model;

use Macopedia\Allegro\Api\CheckoutFormRepositoryInterface;
use Macopedia\Allegro\Model\Api\ClientException;
use Macopedia\Allegro\Model\Api\ClientResponseException;
use Macopedia\Allegro\Model\ResourceModel\Order\CheckoutForm;
use Macopedia\Allegro\Api\Data\CheckoutFormInterface;
use Macopedia\Allegro\Api\Data\CheckoutFormInterfaceFactory;
use Magento\Framework\Exception\NoSuchEntityException;

class CheckoutFormRepository implements CheckoutFormRepositoryInterface
{

    /** @var CheckoutForm */
    private $checkoutForm;

    /** @var CheckoutFormInterfaceFactory */
    private $checkoutFormFactory;

    /**
     * CheckoutFormRepository constructor.
     * @param CheckoutForm $checkoutForm
     * @param CheckoutFormInterfaceFactory $checkoutFormFactory
     */
    public function __construct(CheckoutForm $checkoutForm, CheckoutFormInterfaceFactory $checkoutFormFactory)
    {
        $this->checkoutForm = $checkoutForm;
        $this->checkoutFormFactory = $checkoutFormFactory;
    }

    /**
     * @param string $checkoutFormId
     * @return CheckoutFormInterface
     * @throws ClientException
     * @throws NoSuchEntityException
     */
    public function get(string $checkoutFormId): CheckoutFormInterface
    {
        try {

            $checkoutFormData = $this->checkoutForm->getCheckoutForm($checkoutFormId);

        } catch (ClientResponseException $e) {
            throw new NoSuchEntityException(
                __('Requested checkout form with id "%1" does not exist', $checkoutFormId),
                $e
            );
        }

        $checkoutForm = $this->checkoutFormFactory->create();
        $checkoutForm->setRawData($checkoutFormData);
        return $checkoutForm;
    }
}
