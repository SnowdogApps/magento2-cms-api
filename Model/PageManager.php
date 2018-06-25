<?php

namespace Snowdog\CmsApi\Model;

use Magento\Cms\Api\PageRepositoryInterface;
use Magento\Cms\Model\Template\FilterProvider;
use Snowdog\CmsApi\Api\PageManagerInterface;

class PageManager implements PageManagerInterface
{
    /**
     * @var PageRepositoryInterface
     */
    private $pageRepository;

    /**
     * @var FilterProvider
     */
    private $filterProvider;

    public function __construct(
        PageRepositoryInterface $pageRepository,
        FilterProvider $filterProvider
    ) {
        $this->pageRepository = $pageRepository;
        $this->filterProvider = $filterProvider;
    }

    /**
     * @inheritdoc
     */
    public function getById($pageId)
    {
        $page = $this->pageRepository->getById($pageId);
        $content = $this->getPageContentFiltered($page->getContent());
        $page->setContent($content);

        return $page;
    }

    /**
     * @inheritdoc
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria)
    {
        $pageCollection = $this->pageRepository->getList($searchCriteria);
        $pages = $pageCollection->getItems();
        $parsedPages = [];

        foreach ($pages as $page) {
            $content = $this->getPageContentFiltered($page->getContent());
            $parsedPages[] = $page->setContent($content);
        }

        $pageCollection->setItems($parsedPages);

        return $pageCollection;
    }

    /**
     * @param string $content
     * @return string
     */
    private function getPageContentFiltered($content)
    {
        return $this->filterProvider->getPageFilter()
            ->filter($content);
    }
}
