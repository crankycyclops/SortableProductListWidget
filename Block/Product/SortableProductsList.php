<?php
/**
 * Copyright © James Colannino. All rights reserved.
 * Uses some code from vendor/module-catalog-widget/Block/Product/ProductsList.php,
 * with the following copyright attribution:
 *
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Crankycyclops\SortableProductListWidget\Block\Product;

/**
 * Sortable Catalog Products List widget block
 * Class SortableProductsList
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class SortableProductsList extends \Magento\CatalogWidget\Block\Product\ProductsList {

	// Default field to sort by
	const DEFAULT_SORT_FIELD = 'product_id';

	// Default direction to sort by (possible values are ASC for ascending or
	// DESC for descending)
	const DEFAULT_SORT_DIRECTION = 'ASC';

	/**
	 * Prepare and return a custom sorted product collection. Uses code from
	 * vendor/module-catalog-widget/Block/Product/ProductsList.php.
	 *
	 * @return \Magento\Catalog\Model\ResourceModel\Product\Collection
	 */
	public function createCollection() {

		if (!$this->hasData('sortfield')) {
			$this->setData('sortfield', self::DEFAULT_SORT_FIELD);
		}

		if (!$this->hasData('sortdirection')) {
			$this->setData('sortdirection', self::DEFAULT_SORT_DIRECTION);
		}

		/** @var $collection \Magento\Catalog\Model\ResourceModel\Product\Collection */
		$collection = $this->productCollectionFactory->create();
		$collection->setVisibility($this->catalogProductVisibility->getVisibleInCatalogIds());

		$collection = $this->_addProductAttributesAndPrices($collection)
			->addStoreFilter()
			->setPageSize($this->getPageSize())
			->setCurPage($this->getRequest()->getParam($this->getData('page_var_name'), 1))
			->addAttributeToSort($this->getData('sortfield'), $this->getData('sortdirection'));

		$conditions = $this->getConditions();
		$conditions->collectValidatedAttributes($collection);
		$this->sqlBuilder->attachConditionToCollection($collection, $conditions);

		/**
		 * Prevent retrieval of duplicate records. This may occur when multiselect product attribute matches
		 * several allowed values from condition simultaneously
		 */
		$collection->distinct(true);

		return $collection;
	}
}

