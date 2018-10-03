<?php

namespace Snowdog\CmsApi\Api\Data;

interface PageInterface extends \Magento\Cms\Api\Data\PageInterface
{
    /**#@+
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const STORE_ID = 'store_id';
    /**#@-*/

    /**
     * @return int[]
     */
    public function getStoreId();

    /**
     * @param int[] $storeIds
     * @return BlockInterface
     */
    public function setStoreId(array $storeIds);
}
