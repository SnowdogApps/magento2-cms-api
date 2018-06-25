<?php

namespace Snowdog\CmsApi\Api;

interface PageManagerInterface
{
    /**
     * @param int $pageId
     * @return \Magento\Cms\Api\Data\PageInterface
     */
    public function getById($pageId);

    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Magento\Cms\Api\Data\PageSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);
}
