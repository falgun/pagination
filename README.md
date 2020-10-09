# Pagination

Easy to use Pagination for Falgun Framework.

## Install
 *Please not that  PHP 7.4 or higher is required.*

Via Composer

``` bash
$ composer require falgunphp/pagination
```

## Basic Usage
```php
<?php
use Falgun\Pagination\Pagination;

$currentPage = 1;
$itemsPerPage = 10;
$maxLinkToShow = 5;
    
$pagination = new Pagination($currentPage, $itemsPerPage, $maxLinkToShow);

// set total item count we got from db/api
$pagination->setTotalItems(1000);

$paginationBag = $pagination->make();
// $paginationBag is a instance of Falgun\Pagination\PaginationBag
// It has four properties
//    public Page $firstPage;
//    public Page $lastPage;
//    public Page $prePage;
//    public Page $nextPage;
//    public Iterator $links;

// You have to build your own html pagination from this $paginationBag
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
