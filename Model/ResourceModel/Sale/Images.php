<?php

namespace Macopedia\Allegro\Model\ResourceModel\Sale;

use Macopedia\Allegro\Logger\Logger;
use Macopedia\Allegro\Model\Api\Client;
use Macopedia\Allegro\Model\Api\ClientException;
use Macopedia\Allegro\Model\Api\ClientResponseErrorException;
use Macopedia\Allegro\Model\Api\ClientResponseException;
use Macopedia\Allegro\Model\Api\TokenProvider;
use Macopedia\Allegro\Model\ResourceModel\AbstractResource;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Webapi\Rest\Request as MagentoRequest;
use Magento\Framework\Filesystem\Driver\File;

/**
 * Resource model to post Images to Allegro API
 */
class Images extends AbstractResource
{
    /** @var File */
    private $file;

    public function __construct(
        ScopeConfigInterface $scopeConfig,
        Client $client,
        TokenProvider $tokenProvider,
        Logger $logger,
        File $file
    ) {
        parent::__construct($scopeConfig, $client, $tokenProvider, $logger);
        $this->file = $file;
    }

    /**
     * @param string $imagePath
     * @return array
     * @throws ClientException
     * @throws ClientResponseErrorException
     * @throws ClientResponseException
     * @throws FileSystemException
     */
    public function postImage(string $imagePath)
    {
        return $this->sendRawRequest(
            'sale/images',
            MagentoRequest::HTTP_METHOD_POST,
            $this->file->fileGetContents($imagePath),
            \mime_content_type($imagePath)
        );
    }
}
