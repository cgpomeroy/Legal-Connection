<?php

    namespace Drupal\legalc\Custom;
    
    use Drupal\legalc\Custom;
    
    class Colors
    {
        
        //private $randomColor;
        //private $colors = array();
        
        public function __construct()
        {
            //$this->randomColor = new Custom\RandomColor();
        }
        
        public static function getUserColor($uid)
        {
            //Get user color from db
            //If none found, generate, save in db, return it
            return RandomColor::one();
            //return '#18CFBB';
        }
        
        public static function getMatterColor($mid)
        {
            //Get matter color from db
            //If none found, generate, save in db, return it
            return RandomColor::one();
            //return '#CF1867';
        }
        
        public static function getRandomColor()
        {
            return RandomColor::one();
        }
        
        
    }