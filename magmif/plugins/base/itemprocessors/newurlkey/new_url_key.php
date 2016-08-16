<?php
/**
 * Class NewUrlKeyItemProcessor
 * @author Ajai
 *
 * This class is processing url_key for Magento CE 1.8 / Magento Enterprise 1.13
 */
class NewUrlKeyItemProcessor extends Magmi_ItemProcessor
{

    public function getPluginInfo()
    {
        return array(
            "name" => "Magento 1.8 url_key",
            "author" => "Ajai",
            "version" => "0.0.1b",
            "url" => '#'
        );
    }

    public function processItemAfterId(&$item, $params = null)
    {
        if (isset($item['url_key'])) {
            $attribute = $this->getAttrInfo('url_key');
            $storeIds = $this->getItemStoreIds($item);

            $urlKeyTable = $this->tablename($attribute['backend_table']?:'catalog_product_entity_url_key');

            $productId = $params['product_id'];
            $urlKey = $item['url_key'];

            foreach ($storeIds as $storeId) {
                $sql = "INSERT INTO {$urlKeyTable} (entity_type_id, attribute_id, store_id, entity_id, value)
                    VALUES (?,?,?,?,?) ON DUPLICATE KEY UPDATE `value`=VALUES(`value`)";

                $urlId = $this->insert($sql, array($attribute['entity_type_id'], $attribute['attribute_id'], $storeId, $productId, $urlKey));
            }
        }

        return true;
    }

}