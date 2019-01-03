<?php

/**
 * Copyright © James Colannino. All rights reserved.
 * Uses some code from vendor/module-catalog-widget/Block/Product/ProductsList.php,
 * with the following copyright attribution:
 *
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Crankycyclops\SortableProductListWidget\Model\Config;

/**
 * Returns a list of product attributes that the list can be sorted by.
 */
class SortFieldSelect implements \Magento\Framework\Option\ArrayInterface {

	/**
	 * @var \Magento\Framework\Api\SortOrderBuilder
	 */
	protected $sortOrderBuilder;

	/**
	 * @var \Magento\Framework\Api\SearchCriteriaBuilder
	 */
	protected $searchCriteriaBuilder;

	/**
	 * @var \Magento\Eav\Api\AttributeRepositoryInterface
	 */
	protected $attributeRepository;

	public function __construct(
		\Magento\Framework\Api\SortOrderBuilder $sortOrderBuilder,
		\Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder,
		\Magento\Eav\Api\AttributeRepositoryInterface $attributeRepository
	) {
		$this->sortOrderBuilder = $sortOrderBuilder;
		$this->searchCriteriaBuilder = $searchCriteriaBuilder;
		$this->attributeRepository = $attributeRepository;

		$sortOrder = $this->sortOrderBuilder
			->setField('attribute_code')
			->setDirection(\Magento\Framework\Api\SortOrder::SORT_ASC)
			->create();

		$this->searchCriteriaBuilder->setSortOrders([$sortOrder]);
	}

	/**
	 * Returns all possible product attributes. Adapted from solution found here:
	 * https://magento.stackexchange.com/questions/161420/magento-2-how-to-get-all-user-defined-attributes-in-product-model/161426#161426
	 */
	public function toOptionArray() {

		$searchCriteria = $this->searchCriteriaBuilder->create();
		$attributeRepository = $this->attributeRepository->getList(
			'catalog_product',
			$searchCriteria
		);

		$attributes = [];
		foreach($attributeRepository->getItems() as $attribute) {
			$attributes[] = ['value' => $attribute->getAttributeCode(), 'label' => $attribute->getAttributeCode()];
		}

		return $attributes;
	}
}

