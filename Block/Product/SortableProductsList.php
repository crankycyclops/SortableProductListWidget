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

	/**
	 * Prepare and return a custom sorted product collection. Uses code from
	 * vendor/module-catalog-widget/Block/Product/ProductsList.php.
	 *
	 * @return \Magento\Catalog\Model\ResourceModel\Product\Collection
	 */
	public function createCollection()
	{
		/** @var $collection \Magento\Catalog\Model\ResourceModel\Product\Collection */
		$collection = $this->productCollectionFactory->create();
		$collection->setVisibility($this->catalogProductVisibility->getVisibleInCatalogIds());

		// TODO: I'm just sorting on one hardcoded field right now to make the
		// module work. Once I've got it working, I'll start working on making it
		// configurable.
		$collection = $this->_addProductAttributesAndPrices($collection)
			->addStoreFilter()
			->setPageSize($this->getPageSize())
			->setCurPage($this->getRequest()->getParam($this->getData('page_var_name'), 1))
			->addAttributeToSort('publication_date', 'DESC');

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

