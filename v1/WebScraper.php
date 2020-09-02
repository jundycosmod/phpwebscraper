<?php

class WebScraper {

    protected $baseUrl;

    protected $query;

    protected $stores;

    public function __construct($baseUrl, $query)
    {
        $this->baseUrl = $baseUrl;
        $this->query = $query;
    }

    public function getItems($pages)
    {
        $items = [];
        $this->stores = [];
        for($i = 1; $i <= $pages; $i++){
            $pageQuery = $i ? "&ref=pagination&page=".$i : "";
            $html = $this->getHtmlContents($this->baseUrl."search", $this->query.$pageQuery, "GET");

            if($xpath = $this->getXPath($html)){

                $rootElement = "//ul[contains(concat(' ',normalize-space(@class),' '),'responsive-listing-grid wt-grid wt-grid--block justify-content-flex-start pl-xs-0')]";
    
                //get all the product names
                $productNames = $xpath->query($rootElement."//div/h3[contains(concat(' ',normalize-space(@class),' '),'text-gray')]");
                $storeNames = $xpath->query($rootElement."//p[contains(concat(' ',normalize-space(@class),' '),'text-gray-lighter')]");
                $ratings = $xpath->query($rootElement."//input[@name='rating']/@value");
                $numberOfRatings = $xpath->query($rootElement."//div/span/span[contains(concat(' ',normalize-space(@class),' '),'text-body-smaller text-gray-lighter display-inline-block icon-b-1')]");
                $prices = $xpath->query($rootElement."//span[contains(concat(' ',normalize-space(@class),' '),'n-listing-card__price')]");


                $counter = 0;
                $salesPricePattern = "Sale Price";
                if($productNames->length > 0){
                    foreach($productNames as $row){

                        $price = explode("\n", $prices[$counter]->nodeValue);
                        if(!empty($price))
                        {
                            $price = preg_grep('/'.$salesPricePattern.'\s.*/', $price);

                            if(!empty($price)){
                                foreach($price as $salePrice){
                                    
                                    $price = str_replace($salesPricePattern, "", $salePrice);
                                }
                                
                            }else{
                                
                                $price = $prices[$counter]->nodeValue;
                            }
                            
                        }
                        

                        $items[] = [
                            'productName' => trim($row->nodeValue),
                            'storeName' => trim($storeNames[$counter]->nodeValue),
                            'ratings' => trim($ratings[$counter]->nodeValue),
                            'numberOfRating' => trim($numberOfRatings[$counter]->nodeValue),
                            'price' => trim(str_replace("FREE shipping", "", $price))
                        ];
                        $this->stores[trim($storeNames[$counter]->nodeValue)] = trim($storeNames[$counter]->nodeValue);
                        $counter++;
                    }
                }
            }   
        }
        
        return $items;
    }

    public function getStores(){

        $storeDetails = [];
        foreach($this->stores as $store){
            $html = $this->getHtmlContents($this->baseUrl."shop/". $store, "", "GET");
 
            if($xpath = $this->getXPath($html)){
                $storeHeadline = $xpath->query("//span[contains(concat(' ',normalize-space(@data-key),' '),'headline')]");
                $storeLocation = $xpath->query("//span[contains(concat(' ',normalize-space(@data-key),' '),'user_location')]");
                $storeSales = $xpath->query("//div/span[contains(concat(' ',normalize-space(@class),' '),'wt-text-body-01 no-wrap')]");
                $storeRatings = $xpath->query("//input[@name='rating']/@value");
                $storeFaves = $xpath->query("//a[contains(@href,'/favoriters')]");

                if($storeHeadline->length > 0){
                    foreach($storeHeadline as $row){

                        $storeDetails[$store] = [
                            'storeName' => $store,
                            'storeHeadline' => trim($row->nodeValue),
                            'storeLocation' => trim($storeLocation[0]->nodeValue),
                            'storeSales' => intval(trim($storeSales[0]->nodeValue)),
                            'storeRatings' => trim($storeRatings[0]->nodeValue),
                            'storeFaves' => intval(trim($storeFaves[0]->nodeValue))
                        ];
            
                    }
                }
            }
            
        }
        return $storeDetails;
    }

    public function saveStores(){
        $csv = fopen('stores.csv', 'w');
        $stores = $this->getStores();

        if(!empty($stores)){
            foreach ($stores as $fields) {
                fputcsv($csv, $fields);
            }
        }
        
        fclose($csv);
    }

    public function saveItems($numberOfPages){
        $csv = fopen('items.csv', 'w');
        $items = $this->getItems($numberOfPages);

        if(!empty($items)){
            foreach ($items as $fields) {
                fputcsv($csv, $fields);
            }
        }
        
        fclose($csv);
    }

    protected function getXPath($html){
        $doc = new DOMDocument();

        libxml_use_internal_errors(TRUE); 

        if(!empty($html)){ 

            $doc->loadHTML($html);
            libxml_clear_errors(); 
            
            return new DOMXPath($doc);
        }

        return null;
    }

    protected function getHtmlContents($baseUrl, $query, $method) {

        if (strpos($baseUrl, 'shop') !== false) {
            return file_get_contents($baseUrl.$query);
        }

        $url = $baseUrl;
        if ($query != "") {
            $url .= "?" . $query;
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $output = curl_exec($ch);
        curl_close($ch);
        
        return $output;

    }    

}
error_reporting(E_ALL & ~E_NOTICE);
$webScraper = new WebScraper("https://www.etsy.com/", "q=art%20prints");
// number of pages to be scraped
$pages = 1;
$webScraper->saveItems($pages);
$webScraper->saveStores();