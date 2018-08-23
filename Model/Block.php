<?php

namespace Snowdog\CmsApi\Model;

use Snowdog\CmsApi\Api\Data\BlockInterface;

class Block extends \Magento\Cms\Model\Block implements BlockInterface
{
    /**
     * Receive Block store ids
     *
     * @return int[]
     */
    public function getStoreId()
    {
        return $this->hasData('stores') ? $this->getData('stores') : $this->getData('store_id');
    }

    /**
     * @param array $storeId
     * @return BlockInterface
     */
    public function setStoreId(array $storeId)
    {
        return $this->setData(self::STORE_ID, $storeId);
    }
}
