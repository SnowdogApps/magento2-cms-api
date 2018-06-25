<?php

namespace Snowdog\CmsApi\Model;

use Magento\Cms\Api\BlockRepositoryInterface;
use Magento\Cms\Model\Template\FilterProvider;
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

    public function __construct(
        BlockRepositoryInterface $blockRepository,
        FilterProvider $filterProvider
    ) {
        $this->blockRepository = $blockRepository;
        $this->filterProvider = $filterProvider;
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
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria)
    {
        $blockCollection = $this->blockRepository->getList($searchCriteria);
        $blocks = $blockCollection->getItems();
        $parsedBlocks = [];

        foreach ($blocks as $block) {
            $content = $this->getBlockContentFiltered($block->getContent());
            $parsedBlocks[] = $block->setContent($content);
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
