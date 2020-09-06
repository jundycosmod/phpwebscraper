# phpwebscraper
A PHP webscraper class

<a href="https://packagist.org/packages/jundycosmod/phpwebscraper"><img src="https://poser.pugx.org/jundycosmod/phpwebscraper/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/jundycosmod/phpwebscraper"><img src="https://poser.pugx.org/jundycosmod/phpwebscraper/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/jundycosmod/phpwebscraper"><img src="https://poser.pugx.org/jundycosmod/phpwebscraper/license.svg" alt="License"></a>

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
