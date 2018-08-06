<?php

    namespace Drupal\legalc\Custom;
    
    use Drupal\legalc\Custom;
    
    class Matters
    {
        private $user;
        private $uid;
        private $matters;
        
        public function __construct($user = null, $uid = null)
        {
            $this->user = $user;
            $this->uid = $uid;
            if($user == null){
                $this->user = Custom\LCUser::getUser($uid, $user);
            }
            
            
            /* OLD REMOVE
            if($user != null){
                $this->user = $user;
            }
            else {
                $this->user = Custom\LCUser::getUser();
            }
            */
        }
        
        
        public function BuildMattersPage()
        {
            
            $banner = new Custom\Banner();
            //$banner->SetPlusButton('/node/add/matter?destination=matters');
            $banner->SetSearch(new Custom\Search('matters'));
            $b = $banner->renderBanner();
            
            
            /*
            $tabs = new Custom\Tabs(
                array('All Matters', 'Starred', 'Archived'),
                array('all-matters', 'starred-matters', 'archived-matters')
            ); */
            $tabs = new Custom\Tabs(
                array('All Matters' => array('url' => 'matters/all', 'id' => 'matters'), 
                      'Referrals' => array('url' => 'matters/referred', 'id' => 'referrals'),
                      'Starred' => array('url' => 'matters/starred', 'id' => 'starred'),
                      'Archived' => array('url' => 'matters/archived', 'id' => 'archived')
                )
            );
               
            $tabsRendered = $tabs->render();
            
            
    
            $renderable = [
              '#theme' => 'matters',
              //'#data' => array('banner' => $b, 'tabs' => $tabsRendered),
              '#data' => array('tabs' => $tabsRendered),
              /////'#attached' => array('drupalSettings' => array('legalc' => array('contextMenu' => $contextMenu)))
              /////'#attached' => array('library' => array('legalc/legalc-default'),
              /////                     'drupalSettings' => array('legalc' => array('legalc-default' => array('for' => 'bar', 'baz' => 'test'))))
            ];
            return drupal_render($renderable);
    
        }
        
        
        public function getAllMattersUser($currentMatterID)
        {
            //get all matters by user
            
            $nids = array();
            $connection = \Drupal::database();
            $q = $connection->query("SELECT n.nid FROM {node_field_data} n WHERE n.type = :type ORDER BY n.nid DESC", array(':type' => 'matter'));
            $records = $q->fetchAll();
            foreach ($records as $record) {
                $nids[] = $record->nid;
            }
            $nodes = \Drupal\node\Entity\Node::loadMultiple($nids);
            //kint($nodes);
            
            $res = '<div class="lc-list-matters lc-list-all-matters">';
            foreach($nodes as $k1 => $v1){
                $matter = new Custom\Matter($v1);
                $this->matters[] = $matter;
                $res .= $matter->renderMatterList($currentMatterID);
            }
            $res .= '</div>';
            
            return $res;
        }
        
        public function getAllMattersContextMenu()
        {
            $link1 = new \stdClass();
            $link1->iconClass = 'icon-upload';
            $link1->linkTitle = 'Create Matter';
            $link1->url = '/node/add/matter?destination=matters';
            $contextMenu = array($link1);
            return $contextMenu;
        }
        
        public function getMatterReferrals()
        {
            //Get all referrals a lawyer has not acted on yet
            
        }
        
        public function getAllStarredMattersUser()
        {
            //get all matters by user
            
            
            
            return '<b>List all starred matters</b>';
        }
        
        public function getAllArchivedMattersUser()
        {
            //get all matters by user
            
            
            
            return '<b>List all archived matters</b>';
        }
        
        
        //Get lawyer matter count
        //Is this just open matters?
        public static function GetLawyerMatterCount($uid)
        {
            return '11';
        }
        
    }
    
    