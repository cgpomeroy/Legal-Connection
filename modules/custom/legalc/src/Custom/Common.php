<?php

    namespace Drupal\legalc\Custom;
    
    use Drupal\legalc\Custom;
    
    class Common
    {
        
        public function __construct()
        {
           
        }
     
        public static function firstLettersOfWords($string, $cnt)
        {
            preg_match_all("/[A-Z0-9]/", ucwords(strtolower($string)), $matches);
            //kint($matches);
            $all = $matches[0];
            array_splice($all, $cnt);
            $res = implode('', $all);
            return $res;
        }
        
        public static function duplicateCurrentUrl($includeGet = false)
        {
            $path = \Drupal::request()->getpathInfo();
            $arg  = explode('/',$path);
            //kint($arg);
            //kint($_SERVER["REQUEST_URI"]);
            //kint($_SERVER['QUERY_STRING']);
            
            $uri = $_SERVER["REQUEST_URI"];
            $trim = trim($uri, '/');
            
            if($includeGet){
                $trim .= $_SERVER['QUERY_STRING'];
            }
            return $trim;
        }
        
        
    }