<?php

    namespace Drupal\legalc\Custom;
    
    use Drupal\legalc\Custom;
    
    class Payments
    {
        public $matter;
        public $payments = array();
        
        public function __construct($matter = null)
        {
            $this->matter = $matter;
        }
        
        public function matterPaymentsDisplay()
        {
            
            $nids = $this->getMatterPaymentIDs();
            $this->instantiatePayments($nids, true);
            
            $res .= $this->renderPaymentsList();
            
            return $res;
        }
        
        //query database for quotes ids attached to a matter
        public function getMatterPaymentIDs()
        {
            $connection = \Drupal::database();
            $q = $connection->query("SELECT n.nid FROM {node_field_data} n JOIN {node__field_matter} fm ON n.nid = fm.entity_id ".
                    "JOIN {node__field_quote_paid} qp ON n.nid = qp.entity_id ".
                    "WHERE n.type = :type AND fm.field_matter_target_id = :mid AND qp.field_quote_paid_value = 1 ORDER BY n.nid DESC", 
                    array(':type' => 'quote', ':mid' => $this->matter->nid));
            $records = $q->fetchAll();
            
            $nids = array();
            foreach ($records as $record) {
                $nids[] = $record->nid;
            }
                
            return $nids;
            
            
        }
        
        //create quotes objects with nodes or nids
        private function instantiatePayments($nids, $loadNodes = null)
        {
            if($loadNodes != null){
                
                $nodes = \Drupal\node\Entity\Node::loadMultiple($nids);
                // \Drupal\debugsave\Debugsave::debug($nodes);
                foreach($nodes as $k1 => $v1){
                    $this->payments[] = new Custom\Payment($k1, $v1);
                }
            }
            else {
                foreach($nids as $k1 => $v1){
                    $this->payments[] = new Custom\Payment($v1, null);
                }
            }
        }
        
        private function renderPaymentsList()
        {
            $res = '<div class="lc-list-matters lc-list-all-matters lc-matter-list-payments">';
            foreach($this->payments as $k1 => $v1){
                $res .= $v1->renderPaymentsList();
            }
            $res .= '</div>';
            return $res;
        }
        
        
        
        
    }