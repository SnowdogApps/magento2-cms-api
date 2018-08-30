<?php

namespace Snowdog\CmsApi\Model;

use Snowdog\CmsApi\Api\Data\BlockInterface;

class Block extends \Magento\Cms\Model\Block implements BlockInterface
{
    /**
     * @inheritdoc
     */
    public function getStoreId()
    {
        return $this->_getData(self::STORE_ID);
    }

    /**
     * @inheritdoc
     */
    public function setStoreId(array $storeIds)
    {
        $this->setData(self::STORE_ID, $storeIds);

        return $this;
    }
}
