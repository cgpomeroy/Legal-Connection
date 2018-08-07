<?php

    namespace Drupal\legalc\Custom;
    
    use Drupal\legalc\Custom;
    
    class Matter
    {
        public $nid;
        public $node = null;
        private $state;
        
        public function __construct($node = null, $nid = null)
        {
            $this->node = $node;
            $this->nid = $nid;
            if($node == null && $nid != null){
                $this->node = \Drupal\node\Entity\Node::load($nid);
            }
            //kint($this->node);
        }
        
        public function renderMatterPage()
        {
            $state = Custom\MyMobileDetect::detectState();
            $this->state = $state;
            if($state == 'mobile'){
                return $this->renderMobileMatterPage();
            }
            else {
                return $this->renderDesktopMatterPage();
            }
        }
        
        
        public function renderMobileMatterPage()
        {
            /////$banner = new Custom\Banner(true, 'Matter Details', true);
            $banner = new Custom\Banner();
            $banner->SetSearch(new Custom\Search('matters'));
            $b = $banner->renderBanner();
            
            $mh = new Custom\MatterHeader($this, true);
            $matterHeader = $mh->render();
            
            
            //matter details
            $clientProfileImageUrl = '/sites/default/files/test/tempClient.png';
            $matterTitle = $this->node->getTitle();
            $matterID = $this->node->get('field_lc_matter_id')->getString();
            $inviteLawyerBtn = '<a href="/lawyers/list/'.$this->nid.'/invite" class="matter-detail-add-lawyer-btn"><i class="icon-plus-circled"></i><span>Invite Lawyer</span></a>';
            $referLawyerBtn = '<a href="/lawyers/list/'.$this->nid.'/refer" class="matter-detail-refer-lawyer-btn"><i class="icon-forward"></i><span>Refer to Lawyer</span></a>';
            
            $chatBtn = '<a href="#" class="matter-detail-chat-btn"><i class="icon-chat-empty"></i><span>Chat</span></a>';
            
            //info panel
            $clientName = $this->getClientFullName();
            
            
            //matter tab menu
            $tabs = new Custom\Tabs(
                array('Chat' => array('url' => 'chat?matter='.$this->nid, 'id' => 'chat'), 
                      'Files' => array('url' => 'matter/documents/'.$this->nid, 'id' => 'files'),
                      'Billing' => array('url' => 'matter/billing/'.$this->nid, 'id' => 'billing'),
                      'Account' => array('url' => 'matter/account/'.$this->nid, 'id' => 'account'),
                      'Activity' => array('url' => 'matter/activity/'.$this->nid, 'id' => 'activity'),
                    
                      /////'Invoices' => array('url' => 'matter/invoices/'.$this->nid, 'id' => 'invoices'), 
                      /////'Quotes' => array('url' => 'matter/quotes/'.$this->nid, 'id' => 'quotes'),
                      /////'Files' => array('url' => 'matter/documents/'.$this->nid, 'id' => 'files'),
                      /////'Payments' => array('url' => 'matter/payments/'.$this->nid, 'id' => 'payments')
                )
            );
            $tabsRendered = $tabs->render();
            
            
            
            $renderable = [
              '#theme' => 'matter',
              //'#data' => array('banner' => $b, 'tabs' => $tabsRendered),
              '#data' => array('matterTitle' => $matterTitle, 'matterID' => $matterID, 
                               'titleHeader' => $matterHeader,
                               'clientName' => $clientName, 'tabs' => $tabsRendered),
              /////'#attached' => array('library' => array('legalc/legalc-default'),
              /////                     'drupalSettings' => array('legalc' => array('legalc-default' => array('for' => 'bar', 'baz' => 'test'))))
            ];
            return drupal_render($renderable);
        }
        
        public function renderDesktopMatterPage()
        {
            $banner = new Custom\Banner();
            $b = $banner->renderBanner();
            
            /////$mid = (int)$_GET['matter'];
            
            $matters = new Custom\Matters();
            $mattersPage = $matters->getAllMattersUser($this->nid);
            
            if(is_null($this->nid)){
                $matterTabs = "";
            } else {
                $mid = $this->nid;
                $matterTabs = $this->renderDesktopMatterPageTabsArea();
            }
            
            
            $renderable = [
                '#theme' => 'dashboardDesktop',
                '#data' => array('matterTabs' => $matterTabs, 'mattersSidebar' => $mattersPage),
            ];
            return drupal_render($renderable);
        }
       
        public function renderDesktopMatterPageTabsArea()
        {
            $tabs = new Custom\Tabs(
                array('Chat' => array('url' => 'chat?matter='.$this->nid, 'id' => 'chat'), 
                      'Files' => array('url' => 'matter/documents/'.$this->nid, 'id' => 'files'),
                      'Billing' => array('url' => 'matter/billing/'.$this->nid, 'id' => 'billing'),
                      'Account' => array('url' => 'matter/account/'.$this->nid, 'id' => 'account'),
                      'Activity' => array('url' => 'matter/activity/'.$this->nid, 'id' => 'activity'),
                    
                      //'Invoices' => array('url' => 'matter/invoices/'.$this->nid, 'id' => 'invoices'), 
                      //'Quotes' => array('url' => 'matter/quotes/'.$this->nid, 'id' => 'quotes'),
                      //'Documents' => array('url' => 'matter/documents/'.$this->nid, 'id' => 'documents'),
                      //'Payments' => array('url' => 'matter/payments/'.$this->nid, 'id' => 'payments')
                )
            );
            $tabsRendered = $tabs->render();
            
            $renderable = [
              '#theme' => 'matterDesktop',
              //'#data' => array('banner' => $b, 'tabs' => $tabsRendered),
              '#data' => array('tabs' => $tabsRendered),
              /////'#attached' => array('library' => array('legalc/legalc-default'),
              /////                     'drupalSettings' => array('legalc' => array('legalc-default' => array('for' => 'bar', 'baz' => 'test'))))
            ];
            return drupal_render($renderable);
        }
        
        
        
        public function renderMatterList($currentMatterID)
        {
            $state = Custom\MyMobileDetect::detectState();
            $this->state = $state;
            if($state == 'mobile'){
                return $this->renderMatterListMobile();
            }
            else {
                return $this->renderMatterListDesktop($currentMatterID);
            }
        }
        
        public function renderMatterListMobile()
        {
            $ret = '<div class="lc-list-matters-item" data-matter-nid="'.$this->node->id().'">';
            
                $ret .= '<div class="lc-list-matters-item-inner">';
                
                    $ret .= '<div class="lc-list-matters-item-left">';

                        //matter title
                        $ret .= '<div class="lc-list-matters-item-title">';
                            $ret .= '<a href="/matter/'.$this->node->id().'">'.$this->node->getTitle().'</a>';
                        $ret .= '</div>';
                        
                        //matter id
                        $matterID = $this->node->get('field_lc_matter_id')->getString();
                        //kint($matterID); 
                        $ret .= '<div class="lc-list-matters-item-id">';
                            $ret .= $matterID;
                        $ret .= '</div>';
                        
                        //matter name
                        $clientName = $this->getClientFullName();
                        $ret .= '<div class="lc-list-matters-item-client">';
                            $ret .= '<span class="lc-list-matters-item-client-bookmark"><i class="icon-bookmark-empty"></i></span><span class="lc-list-matters-item-client-name">'.$clientName.'</span>';
                        $ret .= '</div>';
                        
                        //matter documents TODO
                        $ret .= '<div class="lc-list-matters-item-doc-count">';
                            $ret .= '<span class="lc-list-matters-item-doc-doc-icon"><i class="icon-attach-2"></i></span><span class="lc-list-matters-item-doc-doc-count">13 Documents</span>';
                        $ret .= '</div>';
                        
                        

                    $ret .= '</div>';
                    
                    $ret .= '<div class="lc-list-matters-item-right">';

                        $ret .= '<span class="lc-list-matters-item-right-star"><a href="#"><i class="icon-star-empty-1"></i></a></span>'.
                                '<span class="lc-list-matters-item-right-open"><a href="/matter/'.$this->node->id().'"><i class="icon-right-open"></i></a></span>';

                    $ret .= '</div>';
                    
                $ret .= '</div>';
            
            $ret .= '</div>';
            
            return $ret;
        }
        public function renderMatterListDesktop($currentMatterID)
        {
            $nid = $this->node->id();
            $title = $this->node->getTitle();
            $desc = $this->node->get('field_description')->getString();
            $icon = $this->getMatterDisplayIcon();
            //$people = $this->getMatterPeople();
            
            $active = ($currentMatterID == $nid) ? ' active' : ''; 
            
            $ret = '<div class="lc-list-matters-item-v2'.$active.'" data-matter-nid="'.$nid.'" data-matter-desktop-url="matter/'.$nid.'" >';
            
                $ret .= '<div class="lc-list-matters-item-v2-inner">';
                
                    $ret .= '<div class="lc-list-matters-item-v2-left">';
                    
                        $ret .= '<a href="/matter/'.$nid.'">'.$icon.'</a>';
                    
                    $ret .= '</div>';
                    
                    $ret .= '<div class="lc-list-matters-item-v2-right">';
                    
                        $ret .= '<div class="lc-list-matters-item-v2-matter-name">';
                            $ret .= '<a href="/matter/'.$nid.'">'.$title.'</a>';
                        $ret .= '</div>';
                        
                        $ret .= '<div class="lc-list-matters-item-v2-matter-desc">';
                            $ret .= $desc;
                        $ret .= '</div>';
                        
                        $ret .= '<div class="lc-list-matters-item-v2-matter-people">';
                            $ret .= '<span>Members: </span><span>ABC, XYZ</span>';
                        $ret .= '</div>';
                    
                    $ret .= '</div>';
                    
                $ret .= '</div>';
                
            $ret .= '</div>';
            
            return $ret;
            
        }
        
        public function matterInvoices()
        {
            $invoices = new Custom\Invoices($this);
            
            return $invoices->matterInvoicesDisplay();
            
            return 'matter invoices ajax';
        }
        
        public function matterQuotes()
        {
        
            //$res = '<div class="lc-matter-quote-content-wrap">';
            
            //    $res .= '<a href="/node/add/quote?matter='.$this->nid.'&destination=matter/'.$this->nid.'" class="lc-button">Create Quote</a>';
            
            
            //$res .= '</div>';
            
            $quotes = new Custom\Quotes($this);
            
            return $quotes->matterQuotesDisplay();
            
            //return $res;
            
        }
        
        public function matterPayments()
        {
            $payments = new Custom\Payments($this);
            
            return $payments->matterPaymentsDisplay();
            
            return 'matter invoices ajax';
        }
        
        public function matterDocuments()
        {
            
            $documents = new Custom\Documents($this);
            //return $documents->matterDocumentsDisplay();
            $docDisplay = $documents->matterDocumentsDisplay();
            $docContextMenu =  $documents->getContextMenuData();
            return array('html' => $docDisplay, 'contextMenu' => $docContextMenu);
        }
        
        
        //Get the lawyer that currently "owns" it - either the lawyer that created the matter or to which it was referred
        public function getMatterOwnerLawyer($getUserObj = null)
        {
            $connection = \Drupal::database();
            $q = $connection->query("SELECT mo.uid FROM {lc_matter_owner} mo WHERE mo.mid = :mid AND mo.lc_status = (:start OR :accepted) ORDER BY mo.created DESC LIMIT 1", 
                    array(':mid' => $this->nid, ':start' => 'start', ':accepted' => 'accepted'));
            $record = $q->fetch();
            //kint($record->uid);
            if($getUserObj){
                //////////return \Drupal\user\Entity\User::load($record->uid);
                return LCUser::getUser($record->uid);
            }
            return $record->uid;
        }
        
        public function saveNewMatterNewOwner()
        {
            
        }
        
        public function saveMatterNewOwner($referMatterNode = null, $status = null)
        {
            
            $connection = \Drupal::database();
            $status = ($status == null) ? 'start' : $status;
            
            if($referMatterNode == null){
                //This is when the matter is first created
                
                $result = $connection->insert('lc_matter_owner')
                ->fields([
                  'mid' => $this->nid,
                  'cid' => $this->node->getOwnerId(),
                  'uid' => $this->node->getOwnerId(),
                  'rid' => 0,
                  'origin' => 'created',
                  'lc_status' => $status,
                  'created' => time(),
                  
                ])
                ->execute();
                
            }
            else {
                //This is when the matter is referred
                kint_require();
                \Kint::$maxLevels = 5;
                //kint($referMatterNode);
                //kint( $referMatterNode->get('field_referred_lawyer')->getValue() );
                //kint( $referMatterNode->id() );    
                   
                $referredLawyerId = (int)$referMatterNode->get('field_referred_lawyer')->getValue()[0]['target_id'];
                
                
                $result = $connection->insert('lc_matter_owner')
                ->fields([
                  'mid' => $this->nid,
                  'cid' => $this->node->getOwnerId(),
                  'uid' => $referredLawyerId,
                  'rid' => $referMatterNode->id(),
                  'origin' => 'referred',
                  'lc_status' => $status,
                  'created' => time(),
                ])
                ->execute();
                
                
                
                /*
                $result = $connection->insert('lc_matter_owner')
                ->fields([
                  'mid' => $entity->get('field_matter')->getValue(),
                  'cid' => $entity->get('field_matter_referrer')->getValue(),
                  'uid' => $entity->get('field_referred_lawyer')->getValue(),
                  'rid' => $entity->id(),
                  'origin' => 'referred',
                  'lc_status' => $status,
                  'created' => time(),
                  'updated' => time(),
                ])
                ->execute();
                 * 
                 */
                
            }
        }
        
        public function getMatterClient()
        {
            kint_require();
            \Kint::$maxLevels = 5;
        
        
            
            //kint($cid);
            //$cid = array();
            
            //if(empty($cid)){
            if( $this->node->get('field_client')->isEmpty() ){
                
                $firstName = $this->node->get('field_first_name')->getString();
                $lastName = $this->node->get('field_last_name')->getString();
                $email = $this->node->get('field_email_address')->getString();
                $mobile = $this->node->get('field_mobile_number')->getString();
                $landline = $this->node->get('field_landline_number')->getString();
                return array('client' => null, 'clientObj' => null, 'profilePicPath' => null,
                    'fullName' => $firstName.' '.$lastName, 'firstName' => $firstName, 'lastName' => $lastName, 
                    'email' => $email, 'mobile' => $mobile, 'landline' => $landline);
                
            }
            else {
                
                $cid = $this->node->get('field_client')->getValue();  //[0]['target_id'];
                //kint($cid);
                $clientUser = Custom\Client::getUser($cid[0]['target_id']);
                //kint($clientUser);
                //$client = $clientUser->user;
                return $clientUser->getUserDetails();
                
                
                
                
                /////$client = \Drupal\user\Entity\User::load( $cid[0]['target_id'] );
                
                /*
                $firstName = $client->get('field_firstname')->getString();
                $lastName = $client->get('field_last_name')->getString();
                $email = $client->getEmail();
                $mobile = $this->node->get('field_mobile_number')->getString();
                $landline = $this->node->get('field_landline_number')->getString();
                return array('client' => $client, 'firstName' => $firstName, 'lastName' => $lastName, 'email' => $email, 'mobile' => $mobile, 'landline' => $landline);
                */
            }
            
        }
        
        public function getTitle()
        {
            return $this->node->getTitle();
        }
        
        //Get the first letter of each word in the matter title
        //Used for the coloured icon
        public function getMinifiedTitle()
        {
            $letters = Custom\Common::firstLettersOfWords($this->getTitle(), 3);
            return $letters;
        }
        
        public function getClientFullName()
        {
            $client = $this->getMatterClient();
            return $client['fullName'];
        }
        
        public function getMatterDisplayIcon()
        {
            $color = Custom\Colors::getMatterColor($this->nid);
            
            $mT = $this->getMinifiedTitle();
            
            $ret = '<div class="lc-matter-th-color-icon-wrap"><div class="lc-matter-th-color-icon" style="background-color:'.$color.'">'.
                        $mT.
                   '</div></div>';
            
            return $ret;
        }
            
        
        //Get matter lawyers and clients
        public function getMatterPeople()
        {
            //Get current matter owner
            $co = $this->getMatterOwnerLawyer(true);
            
            $uid = $co->getUID();
            $pic = $co->getUserPicturePath();
            $fullName = $co->getUserFullName();
            $shortName = $co->getMinifiedName();
            
            $users = array();
            $users['lawyers'] = array();
            $users['client'] = array();
            $users['lawyers'][] = array('uid' => $uid, 'pic' => $pic, 'fullName' => $fullName, 'color' => 0, 'shortName' => $shortName);
            
            //kint($users);
            
            //TODO: check for any invited lawyers
            
            //Get matter client
            $client = $this->getMatterClient();
            //kint($client);
            $uid = is_null($client['clientObj']) ? 0 : $client['clientObj']->getUID();
            $pic = is_null($client['profilePicPath']) ? 0 : $client['profilePicPath'];
            $color = 0;
            if(!$pic){
                if(!$uid){
                    $color = Custom\Colors::getRandomColor();
                }
                else {
                    $color = Custom\Colors::getUserColor($uid);
                }
            }
            $shortName = "";
            if(!is_null($client['clientObj'])){
                $shortName = $client['clientObj']->getMinifiedName();
            }
            else {
                $shortName = Custom\Common::firstLettersOfWords($client['fullName'], 2);
            }
            $users['client'][] = array('uid' => $uid, 'pic' => $pic, 
                'fullName' => $client['fullName'], 'color' => $color, 'shortName' => $shortName);
            
            //kint($users);
            
            return $users;
            
        }
        
        public function sendNewMatterNewClientEmail()
        {
            //1. CHECK CLIENT PROVIDED EMAIL ADDRESS EXISTS
            //2. IF YES, CLIENT EXISTS - SEND MAIL CONFIRMING MATTER ATTACHMENT
            //3. IF NOT, 
                //4. GENERATE, SAVE TOKEN, SAVE EMAIL ADDRESS THIS IS SENT TO
                //5. SEND MAIL WITH REGISTRATION LINK WITH TOKEN
        }
        
    }