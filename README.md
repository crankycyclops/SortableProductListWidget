Provides a Magento 2 widget that functions as a drop-in replacement for Magneto_CatalogWidget's ProductList, and which provides customizable sorting options.

To install:

```
mkdir -p /path/to/store/thirdparty/Crankycyclops
cd /path/to/store/thirdparty/Crankycyclops
git clone git@github.com:crankycyclops/SortableProductListWidget.git
ln -s /path/to/store/app/code/Crankycyclops /path/to/store/thirdparty/Crankycyclops
php bin/magento module:enable Crankycyclops_SortableProductListWidget
php bin/magento setup:upgrade
```

Make sure to clear your cache, and also to run di:compile if you're in production mode.

TODO: composer package coming soon!
