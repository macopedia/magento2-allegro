<?php

namespace Macopedia\Allegro\Model\OrderImporter;

use Macopedia\Allegro\Api\Data\CheckoutForm\BuyerInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Customer\Model\CustomerFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Api\Data\StoreInterface;

/**
 * Customer model class
 */
class Customer
{
    /** @var CustomerFactory */
    private $customerFactory;

    /** @var CustomerRepositoryInterface */
    private $customerRepository;

    public function __construct(
        CustomerFactory $customerFactory,
        CustomerRepositoryInterface $customerRepository
    ) {
        $this->customerFactory = $customerFactory;
        $this->customerRepository = $customerRepository;
    }

    /**
     * @param BuyerInterface $buyer
     * @param StoreInterface $store
     * @param string $vatId
     * @return CustomerInterface
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function get(BuyerInterface $buyer, StoreInterface $store, $vatId)
    {
        $customer = $this->customerFactory->create();
        $customer->setWebsiteId($store->getWebsiteId());
        $customer->loadByEmail($buyer->getEmail());

        if (!$customer->getId()) {
            $customer
                ->setFirstname($buyer->getFirstName())
                ->setLastname($buyer->getLastName())
                ->setEmail($buyer->getEmail())
                ->setTaxvat($vatId)
                ->setWebsiteId($store->getWebsiteId())
                ->setStore($store)
                ->save();
        }
        return $this->customerRepository->getById($customer->getEntityId());
    }

    /**
     * @param int $customerId
     * @param string $email
     * @param array $addressData
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function update($customerId, $email, array $addressData)
    {
        $customer = $this->customerRepository->getById($customerId);
        $customer
            ->setFirstname($addressData['firstName'] ?? 'undefined')
            ->setLastname($addressData['lastName'] ?? 'undefined')
            ->setEmail($email)
            ->save();
    }
}
