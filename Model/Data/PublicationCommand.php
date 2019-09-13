<?php

namespace Macopedia\Allegro\Model\Data;

use Macopedia\Allegro\Api\Data\PublicationCommandInterface;
use Magento\Framework\DataObject;

class PublicationCommand extends DataObject implements PublicationCommandInterface
{

    const OFFER_ID_FIELD_NAME = 'offer_id';
    const ACTION_FIELD_NAME = 'action';

    /**
     * @param string $offerId
     * @return void
     */
    public function setOfferId(string $offerId)
    {
        $this->setData(self::OFFER_ID_FIELD_NAME, $offerId);
    }

    /**
     * @param string $action
     * @return void
     */
    public function setAction(string $action)
    {
        $this->setData(self::ACTION_FIELD_NAME, $action);
    }

    /**
     * @return string
     */
    public function getOfferId(): ?string
    {
        return $this->getData(self::OFFER_ID_FIELD_NAME);
    }

    /**
     * @return string
     */
    public function getAction(): ?string
    {
        return $this->getData(self::ACTION_FIELD_NAME);
    }

    /**
     * @return array
     */
    public function getRawData(): array
    {
        return [
            'offerCriteria' => [
                [
                    'offers' => [
                        [
                            'id' => $this->getOfferId()
                        ]
                    ],
                    'type' => 'CONTAINS_OFFERS'
                ]
            ],
            'publication' => [
                'action' => $this->getAction()
            ]
        ];
    }
}
