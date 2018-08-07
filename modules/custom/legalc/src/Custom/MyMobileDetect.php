<?php

    namespace Drupal\legalc\Custom;
    
    use Drupal\legalc\Custom;
    
    class MyMobileDetect
    {
        public static $state = null;
        
        public function __construct()
        {
            
        }
        
        public static function detectState()
        {
            if( is_null(self::$state) ){
                $detect = new Custom\Mobile_Detect();
                if( $detect->isMobile() || $detect->isTablet() ){
                    self::$state = 'mobile';
                }
                else {
                    self::$state = 'desktop';
                }
                return self::$state;
            }
            else {
                return self::$state;
            }
        }
    }