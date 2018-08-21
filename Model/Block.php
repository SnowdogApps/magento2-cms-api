<?php

namespace Snowdog\CmsApi\Model;

use Snowdog\CmsApi\Api\Data\BlockInterface;

class Block extends Magento\Cms\Model\Block implements BlockInterface
{
    /**
     * Receive page store ids
     *
     * @return int[]
     */
    public function getStores()
    {
        return $this->hasData('stores') ? $this->getData('stores') : $this->getData('store_id');
    }

    /**
     * @param array $storeIds
     * @return BlockInterface
     */
    public function setStores($storeIds)
    {
        return $this->setData(self::STORES, $storeIds);
    }
}
