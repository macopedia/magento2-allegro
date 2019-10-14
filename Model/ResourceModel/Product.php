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

    /**
     * @param $productId
     * @param $storeId
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getRawMinProductDataWithAllegro($productId, $storeId = 0)
    {
        $allegroOfferIdAttribute = $this->_eavConfig->getAttribute($this->getEntityType(), 'allegro_offer_id');

        $connection = $this->getConnection();

        $select = $connection->select()
            ->from(['e' => 'catalog_product_entity'], ['entity_id','sku'])
            ->joinLeft(
                ['t1' => $allegroOfferIdAttribute->getBackendTable()],
                't1.entity_id = e.entity_id',
                ['allegro_offer_id'=>'value']
            )
            ->where('e.entity_id = :productId')
            ->where('t1.store_id = :storeId')
            ->where('t1.attribute_id = :attributeId')
            ->where('t1.value IS NOT NULL');

        $bind = [
            ':storeId' => (int)$storeId,
            ':productId' => (int)$productId,
            ':attributeId' => (int)$allegroOfferIdAttribute->getId()
        ];

        return $connection->fetchRow($select, $bind);
    }
}
