<?php

    namespace Drupal\legalc\Custom;
    
    use Drupal\legalc\Custom;
    
    class LCMail
    {
        
        public function __construct()
        {
            
        }
        
        public static function sendMail($key, $to, $params)
        {
            $mailManager = \Drupal::service('plugin.manager.mail');
            $module = 'legalc';
            //$key = $key;
            //$to = $to;
            
            $langcode = \Drupal::currentUser()->getPreferredLangcode();
            $send = true;
            $result = $mailManager->mail($module, $key, $to, $langcode, $params, NULL, $send);
            if ($result['result'] !== true) {
              ///drupal_set_message(t('There was a problem sending your message and it was not sent.'), 'error');
            }
            else {
              ///drupal_set_message(t('Your message has been sent.'));
            }
        }
        
        
    }