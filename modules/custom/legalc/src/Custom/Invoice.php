<?php

    namespace Drupal\legalc\Custom;
    
    use Drupal\legalc\Custom;
    
    class Invoice
    {
        private $nid = null;
        private $node = null;
        
        public function __construct($nid = null, $node = null)
        {
            $this->nid = $nid;
            $this->node = $node;
            
        }
        
        public function renderInvoiceDisplayPage()
        {
            if($this->node == null){
                $this->node = \Drupal\node\Entity\Node::load($this->nid);
            }
            
            kint_require();
            \Kint::$maxLevels = 5;
            
            $banner = new Custom\Banner(true, 'Invoice Detail', true);
            //$banner->SetPlusButton('/node/add/matter?destination=matters');
            //$banner->SetSearch(new Custom\Search('matters'));
            $b = $banner->renderBanner();
            
            $quote = new Custom\Quote($this->nid, $this->node);
            $quoteDetails = $quote->getQuoteDetails();
            //kint($quoteDetails);
            
            $paidBtn = '<div class="lc-btn-confirm" data-lc-conf="Are you sure? This will confirm the client has fully paid their bill."><a class="lc-button" href="/quote/paid/'.$this->nid.'">Paid</a></div>';
            
            $renderable = [
              '#theme' => 'invoice',
              //'#data' => array('banner' => $b, 'tabs' => $tabsRendered),
              '#data' => array('quote' => $quoteDetails, 'paidBtn' => $paidBtn),
              /////'#attached' => array('library' => array('legalc/legalc-default'),
              /////                     'drupalSettings' => array('legalc' => array('legalc-default' => array('for' => 'bar', 'baz' => 'test'))))
            ];
            return drupal_render($renderable);
            
        }
        
        public function renderInvoiceList()
        {
            $ret = '<div class="lc-list-matters-item" data-quote-nid="'.$this->node->id().'">';
            
                $ret .= '<div class="lc-list-matters-item-inner">';
                
                    $ret .= '<div class="lc-list-quotes-item-left">';

                        //matter title
                        $ret .= '<div class="lc-list-matters-item-title">';
                            $ret .= '<a href="/invoice/'.$this->node->id().'">'.$this->node->getTitle().'</a>';
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
                                '<span class="lc-list-matters-item-right-open"><a href="/invoice/'.$this->node->id().'"><i class="icon-right-open"></i></a></span>';

                    $ret .= '</div>';
                    
                $ret .= '</div>';
            
            $ret .= '</div>';
            
            return $ret;
        }
        
        
    }