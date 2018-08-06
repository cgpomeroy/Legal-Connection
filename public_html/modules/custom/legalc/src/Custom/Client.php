<?php

    namespace Drupal\legalc\Custom;
    
    use Drupal\legalc\Custom;
    
    class Client extends Custom\LCUser
    {
        public static function getUser($uid = null, $user = null)
        {
            if($user != null){
                if(!isset(self::$users[$user->id()])){
                    self::$users[$user->id()] = new Custom\Client($user->id(), $user);
                    return self::$users[$user->id()];
                }
            }
            else {
                $_uid = ($uid == null) ? \Drupal::currentUser()->id() : $uid;
                if(!isset(self::$users[$_uid])){
                    self::$users[$_uid] = new Custom\Client($_uid);
                    return self::$users[$_uid];
                }
                else {
                    return self::$users[$_uid];
                }
            }
        }
        
        public static function getUserByEmail($mail = null)
        {
            if(is_null($mail)){
                return false;
            }
            $res = user_load_by_mail($mail);
            if($res != false){
                return self::getUser(null, $res);
            }
        }
        
        
        public function __construct($uid = null, $user = null)
        {
            parent::__construct($uid, $user);
        }
        
        
        //GENERATE THE TOKEN FOR WHEN USER WITHOUT ACCOUNT ADDED TO MATTER
        public function newUserMatterToken()
        {
            
        }
        
        
        
    }