<?php

    namespace Drupal\legalc\Custom;
    
    use Drupal\legalc\Custom;
    
    class Banner
    {
        public static $banner = null;
        public static $rendered = "";
        
        //private $showMenuLink;
        private $pageTitle;
        private $showBackLink;
        private $backUri;
        
        
        private $search = null;
        
        
        //public function __construct($showMenuLink, $pageTitle, $showBackLink, $backUri = null)
        public function __construct($pageTitle = null, $showBackLink = null, $backUri = null)
        {
            //$this->showMenuLink = $showMenuLink;
            $this->pageTitle = $pageTitle;
            $this->showBackLink = $showBackLink;
            $this->backUri = $backUri;
            self::$banner = $this;
        }
        
        public function renderBanner()
        {
            $state = MyMobileDetect::detectState();
            $this->state = $state;
            if($state == 'mobile'){
                return $this->renderBannerMobile();
            }
            else {
                return $this->renderBannerDesktop();
            }
        }
        
        private function renderBannerDesktop()
        {
            $path = Custom\Common::duplicateCurrentUrl();
            //kint($path);
            
            
            $menuBanner = "";
            $menuHtml = "";
            
            $menu = new Custom\Menu();
            $menuHtml = $menu->renderMenu();
            
            $menuBanner =   '<div id="lc-banner-menu" class="lc-banner-menu-desktop">'.
                                    '<a href="#"><i class="icon-menu"></i></a>'.
                                '</div>';
            
            $newBtn = '<a href="/node/add/matter?destination=dashboard" class="lc-button"><i class="icon-edit"></i><span>New Matter</span></a>';
            
            $timerBtn = '<a href="#" class="lc-button"><i class="icon-clock"></i><span>Start Timer</span></a>';
            
            $notifications = '<a href="#" class="lc-desktop-bell"><div><i class="icon-bell"></i></div></a>';
            
            $res =  '<div class="lc-banner-wrap-desktop"><div class="lc-banner-desktop-actions">'.
                            $newBtn.$timerBtn.$notifications.$menuBanner. 
                            
                    '</div>'.$menuHtml.'</div>';
            
            self::$rendered = $res;
            return $res;
            
            
            //return 'dashboard desktop version coming';
        }
        
        private function renderBannerMobile()
        {
            $menuBanner = "";
            $menuHtml = "";
            
            $menu = new Custom\Menu();
            $menuHtml = $menu->renderMenu();
            $menuBanner =   '<div id="lc-banner-menu">'.
                                    '<a href="#"><i class="icon-dot-3"></i></a>'.
                                '</div>';
           
            $back = "";
            if($this->showBackLink){
                if($this->backUri != null){
                    $back = '<div id="lc-banner-back"><a href="/'.$this->backUri.'"><i class="icon-left-open-2"></i></a></div>'; 
                }
                else {
                    $back = '<div id="lc-banner-back"><a href="#" onclick="window.history.go(-1); return false;"><i class="icon-left-open-2"></i></a></div>'; 
                }
                
            }
            $title = "";
            if($this->pageTitle != null){
                $title = '<div id="lc-banner-title">'.$this->pageTitle.'</div>';
            }
            
            $left = '<div id="lc-banner-left">'.$back.$title.'</div>';
            
            //$right = '';
            $right = '<div id="lc-banner-right">';
            
            $right .= '<div id="lc-banner-timeline"><a href="#"><i class="icon-clock"></i></a></div>';
            
            $right .= '<div id="lc-banner-notifications"><a href="#"><i class="icon-bell"></i></a></div>';
            
            if($this->search != null){
                $right .= $this->search->renderSearch();
            }
            
            $right .= $menuBanner;
            
            $right .= '</div>'; 
            
            
            /*
            if($this->plusButton == '' && $this->search == null){
                
            } else {
                if($this->plusButton != ''){
                        $right .= $this->plusButton;
                    }
            } */
            
            $res =  '<div id="lc-banner-wrap"><div id="lc-banner">'.
                            $left.
                            $right.
                            
                    '</div>'.$menuHtml.'</div>';
            
            self::$rendered = $res;
            return $res;
        }
        
        
        
        public function SetSearch($search)
        {
            $this->search = $search;
        }
        
        public function setFilter()
        {
            
        }
        
    }