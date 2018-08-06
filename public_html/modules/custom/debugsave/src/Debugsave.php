<?php

    namespace Drupal\debugsave;
    
    class Debugsave
    {
        
        public static function debug($var)
        {
            //kint('test debug save');
            
            //$enc = json_encode($var);
            $enc = serialize($var);
            $connection = \Drupal::database();
            
            //$del = $connection->delete('debugsave')->execute();
            
            $result = $connection->insert('debugsave')
                ->fields([
                  'var_string' => $enc
                ])
                ->execute();
            
        }
        
    }