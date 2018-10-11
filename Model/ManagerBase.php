<?php

namespace Snowdog\CmsApi\Model;

use Magento\Framework\Api\SearchCriteriaInterface;

class ManagerBase
{
    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @return int
     */
    protected function getStoreIdBySearchCriteria(SearchCriteriaInterface $searchCriteria)
    {
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
