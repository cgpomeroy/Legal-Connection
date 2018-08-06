<?php

    namespace Drupal\legalc\Custom;
    
    use Drupal\legalc\Custom;
    
    class Tabs
    {
        
        private $tabData;
        private $useSidebar = 0;
        private $sidebarHtml = '';
        
        public function __construct($tabData)
        {
            $this->tabData = $tabData;
           
        }
        
        public function setSidebar($html)
        {
            $this->useSidebar = 1;
            $this->sidebarHtml = $html;
        }
        
        public function render()
        {
            global $base_url;
            
            $ret = '<div class="lc-tabs-wrap">'.
                        '<div class="lc-tabs-tabs">';
            
            $cnt = 0;
            foreach($this->tabData as $k => $v){
                /* if($cnt == 0){
                    $activeClass = ' active';
                }
                else {
                    $activeClass = '';
                } */
                $activeClass = '';
                
                
                $icon = "";
                if(isset($v['icon'])){
                    $icon = $v['icon'];
                }
                
                $id = "";
                if(isset($v['id'])){
                    $id = $v['id'];
                }
                
                $ret .= '<a href="'.$base_url.'/'.$v['url'].'" class="lc-tabs-link'.$activeClass.'" id="'.$id.'">'.$icon.' '.$k.'</a>';
                $cnt++;
            }
            
            $sidebar = '';
            $parentClass = ' noSidebar';
            if($this->useSidebar){
                $sidebar = '<div class="lc-tabs-content-sidebar">'.$this->sidebarHtml.'</div>';
                $parentClass = ' hasSidebar';
            }
            
            $ret .= '</div>'.
                    '<div class="lc-tabs-content-wrap'.$parentClass.'">'.
                    
                        //'<div class="lc-tabs-content-loader"><img src="/themes/legalconnect/images/preloader.gif" /></div>'.
                        //'<div class="lc-tabs-content-region"></div>'.$sidebar.
                    
                        
                        '<div class="lc-tabs-content-region">'.
                    
                            '<div class="lc-tabs-content-loader"><img src="/themes/legalconnect/images/preloader.gif" /></div>'.
                            '<div class="lc-tabs-content-data"></div>'.
                    
                        '</div>'.$sidebar.
                    
                    '</div>'.
                    
            '</div>';
            
            return $ret;
        }
        
        
    }