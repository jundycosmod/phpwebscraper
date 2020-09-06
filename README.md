# phpwebscraper
A PHP webscraper class

# Installation
composer require jundycosmod/phpwebscraper:dev-master

# Sample Usage

```
$webScraper = new WebScraper([
     'baseUrl' => '<Url of the site to be scraped>',
     'queries' => '<specific queries>',
     'pagination' => '<pagination patterns>',
     'pages' => <number of pages to be scraped>,
     'searches' => [
         '<data1>' => '<xpath pattern of data1>',
         '<data2>' => '<xpath pattern of data2>'
     ],
     'export' => '<type of data export>'
]);
```
