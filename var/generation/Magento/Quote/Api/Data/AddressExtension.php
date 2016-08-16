<?php
namespace Magento\Quote\Api\Data;

/**
 * Extension class for @see \Magento\Quote\Api\Data\AddressInterface
 */
class AddressExtension extends \Magento\Framework\Api\AbstractSimpleObject implements \Magento\Quote\Api\Data\AddressExtensionInterface
{
    /**
     * @return int|null
     */
    public function getGiftRegistryId()
    {
        return $this->_get('gift_registry_id');
    }

    /**
     * @param int $giftRegistryId
     * @return $this
     */
    public function setGiftRegistryId($giftRegistryId)
    {
        $this->setData('gift_registry_id', $giftRegistryId);
        return $this;
    }
}
