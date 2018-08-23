<?php

namespace Snowdog\CmsApi\Api\Data;

/**
 * CMS block interface.
 * @api
 */
interface BlockInterface extends \Magento\Cms\Api\Data\BlockInterface
{
    /**#@+
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const STORE_ID = 'store_id';
    /**#@-*/

    /**
     * Get store ids
     *
     * @return int[]
     */
    public function getStoreId();

    /**
     * @param array $storeId
     * @return BlockInterface
     */
    public function setStoreId(array $storeId);
}
