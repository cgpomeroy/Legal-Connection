<?php

    namespace Drupal\legalc\Custom;
    
    use Drupal\legalc\Custom;
    
    class Billing
    {
        private $mid = null; //matter id
        private $mNode = null; //matter node
        
        public function __construct($nid = null, $node = null)
        {
            $this->mid = $nid;
            $this->mNode = $node;
            
        }
        
        public function matterBillingPage()
        {
            
        }
        
        public function matterBillingDisplay()
        {
            return '<p>List all billing data here.</p>';
        }
        
        public function getContextMenuData()
        {
            //$menuData = array(
            //    array('iconClass' => 'icon-upload', 'linkTitle' => 'File Upload', 'url' => '/node/add/documents?matter=69&destination=matter/69'),
                
            //);
            
            $link1 = new \stdClass();
            $link1->iconClass = 'icon-upload';
            $link1->linkTitle = 'Invoice';
            $link1->url = '/';
            
            $link2 = new \stdClass();
            $link2->iconClass = 'icon-upload';
            $link2->linkTitle = 'Quote';
            $link2->url = '/';
            
            return array($link1, $link2);
            
            
        }
    }