<?php

namespace Snowdog\CmsApi\Model;

use Magento\Cms\Api\Data\PageInterface;
use Magento\Cms\Api\PageRepositoryInterface;
use Magento\Cms\Model\PageFactory;
use Magento\Cms\Model\ResourceModel\Page;
use Magento\Cms\Model\Template\FilterProvider;
use Magento\Framework\Exception\NoSuchEntityException;
use Snowdog\CmsApi\Api\PageManagerInterface;
use Magento\Framework\App\State;
use Magento\Framework\App\Area;

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

    /**
     * @var PageFactory
     */
    private $pageFactory;

    /**
     * @var Page
     */
    private $pageResource;

    /**
     * @var State
     */
    protected $appState;

    public function __construct(
        PageRepositoryInterface $pageRepository,
        FilterProvider $filterProvider,
        PageFactory $pageFactory,
        Page $pageResource,
        State $appState
    ) {
        $this->pageRepository = $pageRepository;
        $this->filterProvider = $filterProvider;
        $this->pageFactory = $pageFactory;
        $this->pageResource = $pageResource;
        $this->appState = $appState;
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
    public function getByIdentifier($identifier, $storeId = null)
    {
        $page = $this->pageFactory->create();
        $page->setStoreId($storeId);
        $this->pageResource->load($page, $identifier, PageInterface::IDENTIFIER);

        if (!$page->getId()) {
            throw new NoSuchEntityException(
                __('CMS Page with identifier "%1" does not exist.', $identifier)
            );
        }

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
        $emulatedResult = $this->appState->emulateAreaCode(
            Area::AREA_FRONTEND,
            [$this->filterProvider->getPageFilter(), 'filter'],
            [$content]
        );

        return $emulatedResult;
    }
}
