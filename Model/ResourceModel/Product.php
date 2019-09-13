<?php

namespace Macopedia\Allegro\Model\ResourceModel;

class Product extends \Magento\Catalog\Model\ResourceModel\Product
{

    /**
     * @param string $allegroOfferId
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getIdByAllegroOfferId(string $allegroOfferId)
    {
        $allegroOfferIdAttribute = $this->_eavConfig->getAttribute($this->getEntityType(), 'allegro_offer_id');

        $connection = $this->getConnection();

        $select = $connection->select()
            ->from(['e' => 'catalog_product_entity'], 'e.entity_id')
            ->join(['t1' => $allegroOfferIdAttribute->getBackendTable()], 't1.entity_id = e.entity_id')
            ->where('t1.value = :allegroOfferId')
            ->where('t1.attribute_id = :attributeId');

        $bind = [
            ':allegroOfferId' => (string)$allegroOfferId,
            ':attributeId' => (int)$allegroOfferIdAttribute->getId()
        ];

        return $connection->fetchOne($select, $bind);
    }
}
