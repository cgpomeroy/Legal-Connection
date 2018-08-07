<?php

    namespace Drupal\legalc\Custom;
    
    use Drupal\legalc\Custom;
    
    class LCUser
    {
        public static $users = array();
        //protected static $namespace = __NAMESPACE__;
        
        public $user;
        
        public static function getUser($uid = null, $user = null)
        {
            if($user != null){
                $roles = $user->getRoles();
            }
            else {
                $uid = ($uid == null) ? \Drupal::currentUser()->id() : $uid;
                $user = \Drupal\user\Entity\User::load($uid);
                $roles = $user->getRoles();
            }
            //kint($roles);
            $uType = 'LCUser';
            if(in_array('law_firm_user', $roles)){
                $uType = 'Lawyer';
            }
            else if(in_array('client', $roles)){
                $uType = 'Client';
            }
            
            if(!isset(self::$users[$user->id()])){
                $className = __NAMESPACE__.'\\'.$uType;
                $className = '\Drupal\legalc\Custom\\'.$uType;
                self::$users[$user->id()] = new $className($user->id(), $user);
            }
            return self::$users[$user->id()];
                
            
            /*
            if($user != null){
                if(!isset(self::$users[$user->id()])){
                    self::$users[$user->id()] = new Custom\LCUser($user->id(), $user);
                    return self::$users[$user->id()];
                }
            }
            else {
                $_uid = ($uid == null) ? \Drupal::currentUser()->id() : $uid;
                if(!isset(self::$users[$_uid])){
                    self::$users[$_uid] = new Custom\LCUser($_uid);
                    return self::$users[$_uid];
                }
                else {
                    return self::$users[$_uid];
                }
            } */
            
        }
        
        
        
        public static function getCurrentUserUid()
        {
            return \Drupal::currentUser()->id();
        }
        
        
        public function __construct($uid = null, $user = null)
        {
            if($user != null){
                $this->user = $user;
            }
            else {
                if($uid == null){
                    $current_user = \Drupal::currentUser();
                    $this->user = \Drupal\user\Entity\User::load($current_user->id());
                }
                else {
                    $this->user = \Drupal\user\Entity\User::load($uid);
                }
            }
            
            
            //kint($this->user);
        }
        
        public function getUserDetails()
        {
            $ret = array('client' => $this->user, 'clientObj' => $this, 'profilePicPath' => getUserPicturePath(),
                        'fullName' => $this-> getUserFullName(), 'firstName' => $this->getUserFirstName(), 'lastName' => $this->getUserLastName(), 
                        'email' => $this->getUserEmailAddress(), 'mobile' => $this->getUserMobile(), 'landline' => $this->getLandline());
            return $ret;
        }
        
        public function getUID()
        {
            return $this->user->id();
        }
        
        public function getUserFullName()
        {
            return $this->getUserFirstName().' '.$this->getUserLastName();
        }
        public function getUserFirstName()
        {
            $firstName = $this->user->get('field_firstname')->getString();
            return $firstName;
        }
        public function getUserLastName()
        {
            $firstName = $this->user->get('field_last_name')->getString();
            return $firstName;
        }
        
        public function getUserEmailAddress()
        {
            return $this->user->getEmail();
        }
        
        public function getUserPicturePath()
        {
            if (!empty($this->user->user_picture) && $this->user->user_picture->isEmpty() === FALSE) {
                $user_picture = $this->user->user_picture->entity->getFileUri();
                $fp = file_url_transform_relative(file_create_url($user_picture));
                return $fp;
            }
            //TODO: return false; //no profile image //we'll use a color instead
            return '/sites/default/files/test/dkies.jpg';
        }
        
        public function getUserPhoneNumber()
        {
            return $this->getUserMobile(). '|' .$this->getLandline();
        }
        
        public function getUserMobile()
        {
            return $this->user->get('field_mobile_number')->getString();
        }
        
        public function getLandline()
        {
            return $this->user->get('field_landline_number')->getString();
        }
        
        public function getUserRoles()
        {
            return $this->user->getRoles();
        }
        
        public function getMinifiedName()
        {
            $letters = Custom\Common::firstLettersOfWords($this->getUserFullName(), 2);
            return $letters;
        }
        
        
    }