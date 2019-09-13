<?php

namespace Macopedia\Allegro\Controller\Adminhtml;

use Macopedia\Allegro\Api\Data\OfferInterfaceFactory;
use Macopedia\Allegro\Api\Data\ImageInterfaceFactory;
use Macopedia\Allegro\Api\OfferRepositoryInterface;
use Macopedia\Allegro\Api\ParameterDefinitionRepositoryInterface;
use Macopedia\Allegro\Controller\Adminhtml\Offer\Context as OfferContext;
use Macopedia\Allegro\Model\Api\Credentials;
use Macopedia\Allegro\Api\ProductRepositoryInterface;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\Forward;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\Page;
use Psr\Log\LoggerInterface;

abstract class Offer extends Action
{

    /** @var Credentials */
    protected $credentials;

    /** @var ProductRepositoryInterface */
    protected $productRepository;

    /** @var OfferRepositoryInterface */
    protected $offerRepository;

    /** @var OfferInterfaceFactory */
    protected $offerFactory;

    /** @var ParameterDefinitionRepositoryInterface */
    protected $parameterDefinitionRepository;

    /** @var ImageInterfaceFactory */
    protected $imageFactory;

    /** @var Registry */
    protected $registry;

    /** @var LoggerInterface */
    protected $logger;

    /**
     * Offer constructor.
     * @param Context $context
     * @param OfferContext $offerContext
     */
    public function __construct(
        Context $context,
        OfferContext $offerContext
    ) {
        parent::__construct($context);
        $this->credentials = $offerContext->getCredentials();
        $this->productRepository = $offerContext->getProductRepository();
        $this->offerRepository = $offerContext->getOfferRepository();
        $this->offerFactory = $offerContext->getOfferFactory();
        $this->parameterDefinitionRepository = $offerContext->getParameterDefinitionRepository();
        $this->imageFactory = $offerContext->getImageFactory();
        $this->registry = $offerContext->getRegistry();
        $this->logger = $offerContext->getLogger();
    }

    /**
     * @return Forward
     */
    protected function createForwardNoRouteResult()
    {
        /** @var Forward $forward */
        $forward = $this->resultFactory->create(ResultFactory::TYPE_FORWARD);
        $forward->forward('noRoute');
        return $forward;
    }

    /**
     * @return Page
     */
    protected function createPageResult()
    {
        /** @var Page $page */
        $page = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        return $page;
    }

    /**
     * @param string $offerId
     * @return Redirect
     */
    protected function createRedirectEditResult($offerId)
    {
        /** @var Redirect $redirect */
        $redirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $redirect->setPath('allegro/offer/edit', ['id' => $offerId]);
        return $redirect;
    }

    /**
     * @param string $offerId
     * @return Redirect
     */
    protected function createRedirectIndexResult()
    {
        /** @var Redirect $redirect */
        $redirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $redirect->setPath('admin/index/index');
        return $redirect;
    }
}
