<?php

class WebScraper {

    protected $options;

    public function __construct($options)
    {
        $this->options = $options;
    }

    public function get()
    {
        $items = [];
        $this->stores = [];
        for($i = 1; $i <= $this->options['pages']; $i++){
            $pageQuery = $i ? $this->options['pagination'].$i : "";
            $html = $this->getHtmlContents($this->options['baseUrl'], $this->options['queries'].$pageQuery, "GET");

            if($xpath = $this->getXPath($html)){
                
                $items = $this->parseData($xpath);
               
            }   
        }
        
        return $items;
    }

    protected function parseData($xpath)
    {
        $results = [];
        foreach($this->options['searches'] as $searchKey => $searchFilter){
            $searchResults = $xpath->query($searchFilter);
            
            if($searchResults->length > 0){
                $counter = 0;
                foreach($searchResults as $searchResult){
                    $results[$counter][$searchKey] = trim($searchResult->nodeValue);
                    $counter++;
                }
                
            }
            
        }
        
        if($this->options['export'])
        {
            $exportOption = "to".ucfirst($this->options['export']);
            return $this->$exportOption($results);
        }

        return $results;

    }

    protected function toArray($output)
    {
        return $output;
    }

    protected function getXPath($html)
    {
        $doc = new DOMDocument();

        libxml_use_internal_errors(TRUE); 

        if(!empty($html)){ 

            $doc->loadHTML($html);
            libxml_clear_errors(); 
            
            return new DOMXPath($doc);
        }

        return null;
    }

    protected function getHtmlContents($baseUrl, $query, $method) 
    {

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

        if(!$output)
        {
            echo "failed getting the contents of ". $url."\n";
        }
        
        return $output;

    }    

}
