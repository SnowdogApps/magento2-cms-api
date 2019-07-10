<?php

namespace Snowdog\CmsApi\Model;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;

class ManagerBase
{
    /**
     * @var UrlInterface
     */
    private $url;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @param UrlInterface $url
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        UrlInterface $url,
        StoreManagerInterface $storeManager
    ) {
        $this->url = $url;
        $this->storeManager = $storeManager;
    }

    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @return int
     */
    protected function getStoreIdBySearchCriteria(SearchCriteriaInterface $searchCriteria)
    {
        $url = $this->url->getCurrentUrl();
        preg_match('/\/rest\/(.*?)\/V1\//', $url, $scope);

        if (isset($scope[1])) {
            return (int) $this->storeManager->getStore()->getId();
        }

        $filterGroups = $searchCriteria->getFilterGroups();

        $storeIds = [];
        foreach ($filterGroups as $filterGroup) {
            foreach ($filterGroup->getFilters() as $filter) {
                if ($filter->getField() == 'store_id') {
                    $storeIds = array_merge($storeIds, explode(',', $filter->getValue()));
                }
            }

            if (count($storeIds) > 1) {
                return 0; // default store
            }
        }

        return (int) array_shift($storeIds);
    }
}
