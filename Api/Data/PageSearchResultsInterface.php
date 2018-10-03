<?php

namespace Snowdog\CmsApi\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface PageSearchResultsInterface extends SearchResultsInterface
{
    /**
     * @return \Snowdog\CmsApi\Api\Data\PageInterface[]
     */
    public function getItems();

    /**
     * @param \Snowdog\CmsApi\Api\Data\PageInterface[] $items
     * @return PageSearchResultsInterface
     */
    public function setItems(array $items);
}
