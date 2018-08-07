<?php

    namespace Drupal\legalc\Custom;
    
    use Drupal\legalc\Custom;
    
    class Menu
    {
        
        
        public function __construct()
        {
            
        }
        
        public function renderMenu($uid = null)
        {
            
            //$user = new Custom\LCUser();
            $user = Custom\LCUser::getUser();
            $id = $user->getUID();
            $userName = $user->getUserFullName();
            $userEmail = $user->getUserEmailAddress();
            $userPic = $user->getUserPicturePath();
            
            
            
            
            
            
            $renderable = [
                '#theme' => 'legalcMenu',
                '#data' => array('uid' => $id, 'fullname' => $userName, 'email' => $userEmail, 'pic' => $userPic, 'activeMatters' => 3),
            ];
            return drupal_render($renderable);
    
    
            //return '<div id="lc-menu" style="display:none;"></div>';
        }
        
    }