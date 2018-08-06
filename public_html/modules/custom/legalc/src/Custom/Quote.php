<?php

    namespace Drupal\legalc\Custom;
    
    use Drupal\legalc\Custom;
    
    class Quote
    {
        private $nid = null;
        private $node = null;
        
        public function __construct($nid = null, $node = null)
        {
            $this->nid = $nid;
            $this->node = $node;
            
        }
        
        public function renderQuotePage()
        {
            if($this->node == null){
                $this->node = \Drupal\node\Entity\Node::load($this->nid);
            }
            
            kint_require();
            \Kint::$maxLevels = 5;
            
            $banner = new Custom\Banner(true, 'Quote Detail', true);
            //$banner->SetPlusButton('/node/add/matter?destination=matters');
            //$banner->SetSearch(new Custom\Search('matters'));
            $b = $banner->renderBanner();
            
            
            $quoteDetails = $this->getQuoteDetails();
            //kint($quoteDetails);
            
            
            
            $advanceCollectedBtn = '<div class="lc-btn-confirm" data-lc-conf="Are you sure? This will confirm the client has paid the mandatory advance."><a class="lc-button" href="/quote/advance-collected/'.$this->nid.'">Advance collected</a></div>';
            $sendToClientBtn = '<div class="lc-btn-confirm" data-lc-conf="Are you sure? This will send the quote to the client."><a class="lc-button" href="/quote/send-to-client/'.$this->nid.'">Send to client</a></div>';
            $invoiceBtn = '<div class="lc-btn-confirm" data-lc-conf="Are you sure? This will invoice the client with this quote."><a class="lc-button" href="/quote/invoice/'.$this->nid.'">Invoice</a></div>';
            
            
            $renderable = [
              '#theme' => 'quote',
              //'#data' => array('banner' => $b, 'tabs' => $tabsRendered),
              '#data' => array('quote' => $quoteDetails, 'advColBtn' => $advanceCollectedBtn, 'sentToBtn' => $sendToClientBtn, 'invoiceBtn' => $invoiceBtn),
              /////'#attached' => array('library' => array('legalc/legalc-default'),
              /////                     'drupalSettings' => array('legalc' => array('legalc-default' => array('for' => 'bar', 'baz' => 'test'))))
            ];
            return drupal_render($renderable);
            
        }
        
        public function getQuoteDetails()
        {
            if($this->node == null){
                $this->node = \Drupal\node\Entity\Node::load($this->nid);
            }
            
            $quote = array();
            $quote['quote'] = $this->node;
            
            //quote name
            $quoteName = $this->node->getTitle();
            $quote['quoteName'] = $quoteName;
            
           
            //matter name
            $matter = $this->node->get('field_matter')->referencedEntities()[0];
            //kint($matter);
            $quote['matter'] = $matter;
            
            
            $matterTitle = $matter->getTitle();
            //kint($matterTitle);
            $quote['matterTitle'] = $matterTitle;
            
            
            //client details
            //////////////$clientName = $matter->get('field_client_name')->getString();
            //kint($clientName);
            $quote['clientName'] = 'Client name'; //$clientName;
            
            
            //estimated bill
            $billingHours = array();
            $bh = $this->node->get('field_para_bh')->referencedEntities();
            //kint($bh);
            foreach($bh as $k1 => $v1){
                $hours = $v1->get('field_hours')->getValue();
                $hourRate =  $v1->get('field_hour_rate')->getValue();
                //kint($hourRate);
                $desc = $v1->get('field_description')->getString();
                //kint($desc);
                $total =  $v1->get('field_total')->getValue();
                
                $billingHours[] = array('hours' => $hours, 'hourRate' => $hourRate, 'desc' => $desc, 'total' => $total);
            }
            //kint($billingHours);
            $quote['billingHours'] = $billingHours;
            
            
            
            //disbursements
            $disbursements = array();
            $db = $this->node->get('field_disbursements')->referencedEntities();
            //kint($bh);
            foreach($db as $k1 => $v1){
                $hours = $v1->get('field_hours')->getValue();
                $hourRate =  $v1->get('field_hour_rate')->getValue();
                //kint($hourRate);
                $desc = $v1->get('field_description')->getString();
                //kint($desc);
                $total =  $v1->get('field_total')->getValue();
                
                $disbursements[] = array('hours' => $hours, 'hourRate' => $hourRate, 'desc' => $desc, 'total' => $total);
            }
            //kint($disbursements);
            $quote['disbursements'] = $disbursements;
            
            return $quote;
            
        }
        
        public function renderQuotesList()
        {
            $ret = '<div class="lc-list-matters-item" data-quote-nid="'.$this->node->id().'">';
            
                $ret .= '<div class="lc-list-matters-item-inner">';
                
                    $ret .= '<div class="lc-list-quotes-item-left">';

                        $editLink = "";
                        if($this->canEditQuote()){
                            $editLink = '<a href="/node/'.$this->node->id().'/edit" class="content-list-edit-link">edit</a>';
                        }
                        
                        //matter title
                        $ret .= '<div class="lc-list-matters-item-title">';
                            $ret .= '<a href="/quote/'.$this->node->id().'">'.$this->node->getTitle().'</a> - '.$editLink;
                        $ret .= '</div>';
                        
                        //quote id
                        /////$matterID = $this->node->get('field_lc_matter_id')->getString();
                        //kint($matterID); 
                        $ret .= '<div class="lc-list-matters-item-id">';
                            $ret .= 'What is this?';
                        $ret .= '</div>';
                        
                        //quote date
                        /////$clientName = $this->node->get('field_client_name')->getString();
                        $ret .= '<div class="lc-list-matters-item-client">';
                            $ret .= '<span class="lc-list-matters-item-client-bookmark"><i class="icon-calendar-1"></i></span><span class="lc-list-matters-item-client-name">'.'23 Mar, 2017???'.'</span>';
                        $ret .= '</div>';
                        
                        //quote amount pending
                        $ret .= '<div class="lc-list-matters-item-doc-count">';
                            $ret .= '<span class="lc-list-matters-item-doc-doc-icon"><i class="icon-calc"></i></span><span class="lc-list-matters-item-doc-doc-count">R 250 Pending</span>';
                        $ret .= '</div>';
                        
                        //quote if pending
                        $ret .= '<div class="lc-matter-list-quote-pending">';
                            $ret .= '<span class="lc-list-matters-item-doc-doc-icon"><i class="icon-clock"></i></span><span class="lc-list-matters-item-doc-doc-count">Pending</span>';
                        $ret .= '</div>';
                        

                    $ret .= '</div>';
                    
                    $ret .= '<div class="lc-list-matters-item-right">';

                        $ret .= //'<span class="lc-list-matters-item-right-star"><a href="#"><i class="icon-star-empty-1"></i></a></span>'.
                                '<span class="lc-list-matters-item-right-open"><a href="/quote/'.$this->node->id().'"><i class="icon-right-open"></i></a></span>';

                    $ret .= '</div>';
                    
                $ret .= '</div>';
            
            $ret .= '</div>';
            
            return $ret;
        }
        
        public function canEditQuote($uid = null)
        {
            return true;
        }
        
    }