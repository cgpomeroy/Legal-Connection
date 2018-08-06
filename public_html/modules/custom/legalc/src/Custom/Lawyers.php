<?php

    namespace Drupal\legalc\Custom;
    
    use \Drupal\user\Entity\User;
    use Drupal\legalc\Custom;
    
    class Lawyers
    {
        
        public function __construct()
        {
            
        }
        
        public function listLawyersPage($nid, $action)
        {
            
            /////if($action == 'refer'){
                
                return $this->listLawyersPageRefer($nid, $action);
                
            /////}
            
            
            
            
            
        }
        
        public function listLawyersPageRefer($nid, $action)
        {
            
            $banner = new Custom\Banner(true, 'Select Lawyer', true);
            //$banner->SetPlusButton('#');
            $banner->SetSearch(new Custom\Search('lawyers'));
            $b = $banner->renderBanner();
            
            
            $tabs = new Custom\Tabs(
                array('All Lawyers' => array('url' => 'lawyers/all/'.$nid.'/'.$action, 'id' => $action.'-all-lawyers'), 
                      'My Network' => array('url' => 'lawyers/network/'.$nid.'/'.$action, 'id' => $action.'-my-network')
                )
            );
            $tabsRendered = $tabs->render();
    
            
            $renderable = [
              '#theme' => 'listLawyers',
              //'#data' => array('banner' => $b, 'tabs' => $tabsRendered),
              '#data' => array('tabs' => $tabsRendered),
              /////'#attached' => array('library' => array('legalc/legalc-default'),
              /////                     'drupalSettings' => array('legalc' => array('legalc-default' => array('for' => 'bar', 'baz' => 'test'))))
            ];
            return drupal_render($renderable);
            
            
            
        }
        
        
        public function listAllLawyers($nid, $action)
        {
            $users = $this->getAllLawyers($nid, $action);
            
            kint_require();
            \Kint::$maxLevels = 6;
            //kint($users);
            
            $ret = '';
            
            foreach($users as $k1 => $v1){
                //kint($k1);
                //kint($v1);
                //kint($v1->id());
                $lu = Lawyer::getUser($k1, $v1);
                //kint($lu);
                $ret .= $lu->renderListing($action, $nid);
            }
            
            return $ret;
        }
        
        public function getAllLawyers($nid, $action)
        {
            
            $ids = \Drupal::entityQuery('user')
            ->condition('status', 1)
            ->condition('roles', 'law_firm_user')
            ->execute();
            $users = User::loadMultiple($ids);

            return $users;
            
        }
        
    }