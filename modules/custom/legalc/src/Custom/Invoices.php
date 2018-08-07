<?php

    namespace Drupal\legalc\Custom;
    
    use Drupal\legalc\Custom;
    
    class Invoices
    {
        public $matter;
        public $invoices = array();
        
        public function __construct($matter = null)
        {
            $this->matter = $matter;
        }
        
        public function matterInvoicesDisplay()
        {
            //$res = '<div class="lc-matter-quote-content-wrap">';
            
                //$res .= '<a href="/node/add/quote?matter='.$this->matter->nid.'&destination=matter/'.$this->matter->nid.'" class="lc-button">Create Quote</a>';
            
            //$res .= '</div>';
            
            
            $nids = $this->getMatterInvoiceIDs();
            $this->instantiateInvoices($nids, true);
            
            $res .= $this->renderInvoiceList();
            
            return $res;
        }
        
        //Database query matter invoice nids
        public function getMatterInvoiceIDs()
        {
            $connection = \Drupal::database();
            $q = $connection->query("SELECT n.nid FROM {node_field_data} n JOIN {node__field_matter} fm ON n.nid = fm.entity_id ".
                    "JOIN {node__field_quote_invoiced} qi ON n.nid = qi.entity_id ".
                    "WHERE n.type = :type AND fm.field_matter_target_id = :mid AND qi.field_quote_invoiced_value = 1 ORDER BY n.nid DESC", 
                    array(':type' => 'quote', ':mid' => $this->matter->nid));
            $records = $q->fetchAll();
            
            $nids = array();
            foreach ($records as $record) {
                $nids[] = $record->nid;
            }
                
            return $nids;
        }
        
        //Create Invoice objects with either nid or node
        public function instantiateInvoices($nids, $loadNodes = null)
        {
            if($loadNodes != null){
                
                $nodes = \Drupal\node\Entity\Node::loadMultiple($nids);
                // \Drupal\debugsave\Debugsave::debug($nodes);
                foreach($nodes as $k1 => $v1){
                    $this->invoices[] = new Custom\Invoice($k1, $v1);
                }
            }
            else {
                foreach($nids as $k1 => $v1){
                    $this->invoices[] = new Custom\Invoice($v1, null);
                }
            }
        }
        
        public function renderInvoiceList()
        {
            $res = '<div class="lc-list-matters lc-list-all-matters lc-matter-list-invoices">';
            foreach($this->invoices as $k1 => $v1){
                $res .= $v1->renderInvoiceList();
            }
            $res .= '</div>';
            return $res;
        }
        
        
        
    }