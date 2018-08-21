<?php

namespace Snowdog\CmsApi\Model;

use Magento\Cms\Api\BlockRepositoryInterface;
use Magento\Cms\Api\Data\BlockInterface;
use Magento\Cms\Model\BlockFactory;
use Magento\Cms\Model\ResourceModel\Block;
use Magento\Cms\Model\Template\FilterProvider;
use Magento\Framework\Exception\NoSuchEntityException;
use Snowdog\CmsApi\Api\BlockManagerInterface;

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

    public function __construct(
        BlockRepositoryInterface $blockRepository,
        FilterProvider $filterProvider,
        BlockFactory $blockFactory,
        Block $blockResource
    ) {
        $this->blockRepository = $blockRepository;
        $this->filterProvider = $filterProvider;
        $this->blockFactory = $blockFactory;
        $this->blockResource = $blockResource;
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
        $blockCollection = $this->blockRepository->getList($searchCriteria);
        $blocks = $blockCollection->getItems();
        $parsedBlocks = [];

        foreach ($blocks as $block) {
            $content = $this->getBlockContentFiltered($block['content']);
            $block['content'] = $content;
            $parsedBlocks[] = $block;
        }

        $blockCollection->setItems($parsedBlocks);

        return $blockCollection;
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
