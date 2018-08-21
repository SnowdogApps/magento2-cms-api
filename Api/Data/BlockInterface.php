<?php

namespace Snowdog\CmsApi\Api\Data;

/**
 * CMS block interface.
 * @api
 */
interface BlockInterface extends Magento\Cms\Api\Data\BlockInterface
{
    /**#@+
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const STORES = 'stores';
    /**#@-*/

    /**
     * Get store ids
     *
     * @return array|null
     */
    public function getStores();

    /**
     * @param array $storeIds
     * @return BlockInterface
     */
    public function setStores($storeIds);
}
