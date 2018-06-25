<?php

namespace Snowdog\CmsApi\Api;

interface BlockManagerInterface
{
    /**
     * @param int $blockId
     * @return \Magento\Cms\Api\Data\BlockInterface
     */
    public function getById($blockId);

    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Magento\Cms\Api\Data\BlockSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);
}
