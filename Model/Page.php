<?php

namespace Snowdog\CmsApi\Model;

use Snowdog\CmsApi\Api\Data\PageInterface;

class Page extends \Magento\Cms\Model\Page implements PageInterface
{
    /**
     * Receive Page store ids
     *
     * @return int[]
     */
    public function getStoreId()
    {
        return $this->hasData('stores') ? $this->getData('stores') : (array)$this->getData('store_id');
    }

    /**
     * @param array $storeId
     * @return PageInterface
     */
    public function setStoreId(array $storeId)
    {
        return $this->setData(self::STORE_ID, $storeId);
    }
}
