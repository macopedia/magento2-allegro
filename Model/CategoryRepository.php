<?php

namespace Macopedia\Allegro\Model;

use Macopedia\Allegro\Api\CategoryRepositoryInterface;
use Macopedia\Allegro\Api\Data\CategoryInterface;
use Macopedia\Allegro\Api\Data\CategoryInterfaceFactory;
use Macopedia\Allegro\Model\ResourceModel\Sale\Categories;
use Magento\Framework\Exception\NoSuchEntityException;
use Macopedia\Allegro\Model\Api\ClientResponseException;

class CategoryRepository implements CategoryRepositoryInterface
{

    /** @var Categories */
    private $categories;

    /** @var CategoryInterfaceFactory */
    private $categoryFactory;

    /**
     * CategoryRepository constructor.
     * @param Categories $categories
     * @param CategoryInterfaceFactory $categoryFactory
     */
    public function __construct(
        Categories $categories,
        CategoryInterfaceFactory $categoryFactory
    ) {
        $this->categories = $categories;
        $this->categoryFactory = $categoryFactory;
    }

    /**
     * {@inheritDoc}
     */
    public function getRootList(): array
    {
        try {

            $data = $this->categories->getRootList();

        } catch (ClientResponseException $e) {
            return [];
        }

        $result = [];
        foreach ($data as $categoryData) {
            /** @var CategoryInterface $category */
            $category = $this->categoryFactory->create();
            $category->setRawData($categoryData);
            $result[] = $category;
        }
        return $result;
    }

    /**
     * {@inheritDoc}
     */
    public function getList(string $parentCategoryId): array
    {
        try {

            $data = $this->categories->getList($parentCategoryId);

        } catch (ClientResponseException $e) {
            return [];
        }

        $result = [];
        foreach ($data as $categoryData) {
            /** @var CategoryInterface $category */
            $category = $this->categoryFactory->create();
            $category->setRawData($categoryData);
            $result[] = $category;
        }
        return $result;
    }

    /**
     * {@inheritDoc}
     */
    public function get(string $categoryId): CategoryInterface
    {
        try {

            $categoryData = $this->categories->get($categoryId);

        } catch (ClientResponseException $e) {
            throw new NoSuchEntityException(__("Requested category with id '%1' does not exist", $categoryId), $e);
        }

        /** @var CategoryInterface $category */
        $category = $this->categoryFactory->create();
        $category->setRawData($categoryData);
        return $category;
    }

    /**
     * {@inheritDoc}
     */
    public function getAllParents(string $categoryId): array
    {
        $result = [$this->get($categoryId)];

        while (($parentId = $result[0]->getParent()) != '') {
            array_unshift($result, $this->get($parentId));
        }

        return $result;
    }
}
