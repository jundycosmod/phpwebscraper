<?php

require "v2/WebScraper.php";

error_reporting(E_ALL & ~E_NOTICE);

//$rootElement = "//ul[contains(@class),'responsive-listing-grid wt-grid wt-grid--block justify-content-flex-start pl-xs-0')]";
$rootElement = "";
$webScraper = new WebScraper([
    'baseUrl' => 'https://www.etsy.com/search',
    'queries' => 'q=art%20prints',
    'pagination' => '&ref=pagination&page=',
    'pages' => 1,
    'searches' => [
        'productName' => $rootElement."//div/h3[contains(@class,'text-gray')]",
        'storeName' => $rootElement."//div/h3[contains(@class,'text-gray-lighter')]",
        'ratings' => $rootElement."//input[@name='rating']/@value",
        'numberOfRating' => $rootElement."//div/span/span[contains(@class, 'text-body-smaller text-gray-lighter display-inline-block icon-b-1')]",
        'price' => $rootElement."//span[contains(concat(' ',normalize-space(@class),' '),'n-listing-card__price')]"
    ],
    'export' => 'array'
]);

// $webScraper = new WebScraper("https://www.etsy.com/", "q=art%20prints");
// number of pages to be scraped
// $pages = 1;
$result = $webScraper->get();
var_dump($result);
// $webScraper->saveStores("shop/");