<?php

namespace Snowdog\CmsApi\Model;

use Magento\Cms\Api\BlockRepositoryInterface;
use Magento\Cms\Api\Data\BlockInterface;
use Magento\Cms\Model\BlockFactory;
use Magento\Cms\Model\ResourceModel\Block;
use Magento\Cms\Model\Template\FilterProvider;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Snowdog\CmsApi\Api\BlockManagerInterface;
use Snowdog\CmsApi\Api\Data\BlockInterfaceFactory;
use Snowdog\CmsApi\Api\Data\BlockSearchResultsInterfaceFactory;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class BlockManager implements BlockManagerInterface
{
    /**
     * @var BlockRepositoryInterface
     */
    private $blockRepository;

    /**
     * @var FilterProvider
     */
    private $filterProvider;

    /**
     * @var BlockFactory
     */
    private $blockFactory;

    /**
     * @var Block
     */
    private $blockResource;

    /**
     * @var BlockSearchResultsInterfaceFactory
     */
    private $searchResultsFactory;

    /**
     * @var Block\CollectionFactory
     */
    private $blockCollectionFactory;

    /**
     * @var CollectionProcessorInterface
     */
    private $collectionProcessor;

    /**
     * @var BlockInterfaceFactory
     */
    private $blockDtoFactory;

    /**
     * @var DataObjectHelper
     */
    private $dataObjectHelper;

    /**
     * @param BlockRepositoryInterface $blockRepository
     * @param FilterProvider $filterProvider
     * @param BlockFactory $blockFactory
     * @param Block $blockResource
     * @param Block\CollectionFactory $blockCollectionFactory
     * @param BlockSearchResultsInterfaceFactory $searchResultsFactory
     * @param CollectionProcessorInterface $collectionProcessor
     * @param BlockInterfaceFactory $blockDtoFactory
     * @param DataObjectHelper $dataObjectHelper
     */
    public function __construct(
        BlockRepositoryInterface $blockRepository,
        FilterProvider $filterProvider,
        BlockFactory $blockFactory,
        Block $blockResource,
        Block\CollectionFactory $blockCollectionFactory,
        BlockSearchResultsInterfaceFactory $searchResultsFactory,
        CollectionProcessorInterface $collectionProcessor,
        BlockInterfaceFactory $blockDtoFactory,
        DataObjectHelper $dataObjectHelper
    ) {
        $this->blockRepository = $blockRepository;
        $this->filterProvider = $filterProvider;
        $this->blockFactory = $blockFactory;
        $this->blockResource = $blockResource;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->blockCollectionFactory = $blockCollectionFactory;
        $this->collectionProcessor = $collectionProcessor;
        $this->blockDtoFactory = $blockDtoFactory;
        $this->dataObjectHelper = $dataObjectHelper;
    }

    /**
     * @inheritdoc
     */
    public function getById($blockId)
    {
        $block = $this->blockRepository->getById($blockId);
        $content = $this->getBlockContentFiltered($block->getContent());
        $block->setContent($content);

        return $block;
    }

    /**
     * @inheritdoc
     */
    public function getByIdentifier($identifier, $storeId = null)
    {
        $block = $this->blockFactory->create();
        $block->setStoreId($storeId);
        $this->blockResource->load($block, $identifier, BlockInterface::IDENTIFIER);

        if (!$block->getId()) {
            throw new NoSuchEntityException(
                __('CMS Block with identifier "%1" does not exist.', $identifier)
            );
        }

        $content = $this->getBlockContentFiltered($block->getContent());
        $block->setContent($content);

        return $block;
    }

    /**
     * @inheritdoc
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria)
    {
        /** @var \Magento\Cms\Model\ResourceModel\Block\Collection $collection */
        $collection = $this->blockCollectionFactory->create();

        $this->collectionProcessor->process($searchCriteria, $collection);

        $items = [];
        /** @var \Magento\Cms\Model\Block $block */
        foreach ($collection->getItems() as $block) {
            $content = $this->getBlockContentFiltered($block->getContent());
            $block->setContent($content);
            $blockDto = $this->blockDtoFactory->create();
            $this->dataObjectHelper->populateWithArray(
                $blockDto,
                $block->getData(),
                \Snowdog\CmsApi\Api\Data\BlockInterface::class
            );
            $items[] = $blockDto;
        }

        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($items);
        $searchResults->setTotalCount($collection->getSize());

        return $searchResults;
    }

    /**
     * @param string $content
     * @return string
     */
    private function getBlockContentFiltered($content)
    {
        return $this->filterProvider->getBlockFilter()
            ->filter($content);
    }
}
