<?php


namespace Macopedia\Allegro\Controller\Adminhtml\Offer;

use Macopedia\Allegro\Api\Data\OfferInterfaceFactory;
use Macopedia\Allegro\Api\Data\ImageInterfaceFactory;
use Macopedia\Allegro\Api\OfferRepositoryInterface;
use Macopedia\Allegro\Model\Api\Credentials;
use \Macopedia\Allegro\Api\ProductRepositoryInterface;
use Magento\Framework\Registry;
use Psr\Log\LoggerInterface;
use Macopedia\Allegro\Api\ParameterDefinitionRepositoryInterface;

class Context
{

    /** @var Credentials */
    private $credentials;

    /** @var ProductRepositoryInterface */
    private $productRepository;

    /** @var OfferRepositoryInterface */
    private $offerRepository;

    /** @var OfferInterfaceFactory */
    private $offerFactory;

    /** @var ParameterDefinitionRepositoryInterface */
    private $parameterDefinitionRepository;

    /** @var ImageInterfaceFactory */
    private $imageFactory;

    /** @var Registry */
    private $registry;

    /** @var LoggerInterface */
    private $logger;

    /**
     * Context constructor.
     * @param Credentials $credentials
     * @param ProductRepositoryInterface $productRepository
     * @param OfferRepositoryInterface $offerRepository
     * @param OfferInterfaceFactory $offerFactory
     * @param ParameterDefinitionRepositoryInterface $parameterDefinitionRepository
     * @param ImageInterfaceFactory $imageFactory
     * @param Registry $registry
     * @param LoggerInterface $logger
     */
    public function __construct(
        Credentials $credentials,
        ProductRepositoryInterface $productRepository,
        OfferRepositoryInterface $offerRepository,
        OfferInterfaceFactory $offerFactory,
        ParameterDefinitionRepositoryInterface $parameterDefinitionRepository,
        ImageInterfaceFactory $imageFactory,
        Registry $registry,
        LoggerInterface $logger
    ) {
        $this->credentials = $credentials;
        $this->productRepository = $productRepository;
        $this->offerRepository = $offerRepository;
        $this->offerFactory = $offerFactory;
        $this->parameterDefinitionRepository = $parameterDefinitionRepository;
        $this->imageFactory = $imageFactory;
        $this->registry = $registry;
        $this->logger = $logger;
    }

    /**
     * @return Credentials
     */
    public function getCredentials(): Credentials
    {
        return $this->credentials;
    }

    /**
     * @return ProductRepositoryInterface
     */
    public function getProductRepository(): ProductRepositoryInterface
    {
        return $this->productRepository;
    }

    /**
     * @return OfferRepositoryInterface
     */
    public function getOfferRepository(): OfferRepositoryInterface
    {
        return $this->offerRepository;
    }

    /**
     * @return OfferInterfaceFactory
     */
    public function getOfferFactory(): OfferInterfaceFactory
    {
        return $this->offerFactory;
    }

    /**
     * @return ParameterDefinitionRepositoryInterface
     */
    public function getParameterDefinitionRepository(): ParameterDefinitionRepositoryInterface
    {
        return $this->parameterDefinitionRepository;
    }

    /**
     * @return ImageInterfaceFactory
     */
    public function getImageFactory(): ImageInterfaceFactory
    {
        return $this->imageFactory;
    }

    /**
     * @return Registry
     */
    public function getRegistry(): Registry
    {
        return $this->registry;
    }

    /**
     * @return LoggerInterface
     */
    public function getLogger(): LoggerInterface
    {
        return $this->logger;
    }
}
