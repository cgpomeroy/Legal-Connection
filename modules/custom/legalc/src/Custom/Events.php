<?php

    namespace Drupal\legalc\Custom;
    
    class Events
    {
        
        
        public function __construct()
        {
            
        }
        
        public static function getLawyerUpcomingEvents($uid)
        {
            
            //query fetch soonest 5 upcoming events
            
            return array(
                array('day' => 17, 'month' => 'July', 'eventTitle' => 'Upload documents for San Joe\'s Matter and 2 stamp papers', 'due' => 'Due today'),
                array('day' => 19, 'month' => 'July', 'eventTitle' => 'Upload documents for San Joe\'s Matter and 2 stamp papers', 'due' => '21-7-2018'),
            );
            
        }
        
        
    }