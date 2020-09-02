<?php

require "v2/WebScraper.php";

error_reporting(E_ALL & ~E_NOTICE);
$webScraper = new WebScraper("https://www.etsy.com/", "q=art%20prints");
// number of pages to be scraped
$pages = 1;
$webScraper->saveItems("search", $pages);
$webScraper->saveStores("shop/");