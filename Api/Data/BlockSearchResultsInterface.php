<?php

namespace Snowdog\CmsApi\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface BlockSearchResultsInterface extends SearchResultsInterface
{
    /**
     * @return \Snowdog\CmsApi\Api\Data\BlockInterface[]
     */
    public function getItems();

    /**
     * @param \Snowdog\CmsApi\Api\Data\BlockInterface[] $items
     * @return BlockSearchResultsInterface
     */
    public function setItems(array $items);
}
