<?php

    namespace Drupal\legalc\Custom;
    
    use Drupal\legalc\Custom;
    
    class Quotes
    {
        public $matter;
        public $quotes = array();
        
        public function __construct($matter = null)
        {
            $this->matter = $matter;
        }
        
        public function matterQuotesDisplay()
        {
            $res = '<div class="lc-matter-quote-content-wrap">';
            
                $res .= '<a href="/node/add/quote?matter='.$this->matter->nid.'&destination=matter/'.$this->matter->nid.'" class="lc-button">Create Quote</a>';
            
            $res .= '</div>';
            
            
            $nids = $this->getMatterQuoteIDs();
            $this->instantiateQuotes($nids, true);
            
            $res .= $this->renderQuoteList();
            
            return $res;
        }
        
        //query database for quotes ids attached to a matter
        public function getMatterQuoteIDs()
        {
            $connection = \Drupal::database();
            $q = $connection->query("SELECT n.nid FROM {node_field_data} n JOIN {node__field_matter} fm ON n.nid = fm.entity_id ".
                    "WHERE n.type = :type AND fm.field_matter_target_id = :mid ORDER BY n.nid DESC", 
                    array(':type' => 'quote', ':mid' => $this->matter->nid));
            $records = $q->fetchAll();
            
            $nids = array();
            foreach ($records as $record) {
                $nids[] = $record->nid;
            }
                
            return $nids;
            
            
            
            //\Drupal\debugsave\Debugsave::debug($this->quotes);
            
            
        }
        
        //create quotes objects with nodes or nids
        private function instantiateQuotes($nids, $loadNodes = null)
        {
            if($loadNodes != null){
                
                $nodes = \Drupal\node\Entity\Node::loadMultiple($nids);
                // \Drupal\debugsave\Debugsave::debug($nodes);
                foreach($nodes as $k1 => $v1){
                    $this->quotes[] = new Custom\Quote($k1, $v1);
                }
            }
            else {
                foreach($nids as $k1 => $v1){
                    $this->quotes[] = new Custom\Quote($v1, null);
                }
            }
        }
        
        private function renderQuoteList()
        {
            $res = '<div class="lc-list-matters lc-list-all-matters lc-matter-list-quotes">';
            foreach($this->quotes as $k1 => $v1){
                $res .= $v1->renderQuotesList();
            }
            $res .= '</div>';
            return $res;
        }
        
        //Get lawyer matter count
        //Is this just open matters?
        public static function GetLawyerQuoteCount($uid)
        {
            
            return '89';
        }
        
    }