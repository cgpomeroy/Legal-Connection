<?php

    namespace Drupal\legalc\Custom;
    
    use Drupal\legalc\Custom;
    
    class TitleHeader
    {
        protected $showBackLink;
        protected $backUri;
        protected $renderItems = array();
        
        public function __construct($showBackLink = null, $backUri = null)
        {
            //kint('test');
            $this->showBackLink = $showBackLink;
            $this->backUri = $backUri;
        }
        
        public function getBack()
        {
            $back = "";
            if($this->showBackLink){
                if($this->backUri != null){
                    $back = '<div id="lc-titleheader-back"><a href="/'.$this->backUri.'"><i class="icon-left-open-2"></i></a></div>'; 
                }
                else {
                    $back = '<div id="lc-titleheader-back"><a href="#" onclick="window.history.go(-1); return false;"><i class="icon-left-open-2"></i></a></div>'; 
                }
                
            }
            return $back;
        }
        
        public function getTitle($title)
        {
            $title = '<div id="lc-titleheader-title">'.$title.'</div>';
            return $title;
        }
        
        protected function renderItems()
        {
            $res = '<div class="lc-matter-title-header-wrap">';
                
                foreach($this->renderItems as $k => $v){
                    $res .= $v;
                }
            
            $res .= '</div>';
            return $res;
        }
        
    }