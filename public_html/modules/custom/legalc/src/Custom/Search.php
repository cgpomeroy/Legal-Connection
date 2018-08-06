<?php

    namespace Drupal\legalc\Custom;
    
    use Drupal\legalc\Custom;
    
    class Search
    {
        
        public $type;
        
        public function __construct($type)
        {
            $this->type = $type;
        }
        
        public function renderSearch()
        {
            
            //return searchHtml
            return '<div id="banner-search-wrap"><a href="#" class="banner-search-btn"><i class="icon-search"></i></a></div>';
        }
        
        
    }