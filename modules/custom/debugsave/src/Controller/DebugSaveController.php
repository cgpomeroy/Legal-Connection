<?php

namespace Drupal\debugsave\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\debugsave;

class DebugSaveController extends ControllerBase {

  /**
   * Display the markup.
   *
   * @return array
   */
  public function show() {
   
      kint_require();
      \Kint::$maxLevels = 5;


      $connection = \Drupal::database();
      $q = $connection->query("SELECT ds.var_string FROM {debugsave} ds ORDER BY ds.pid DESC LIMIT 5");
      $records = $q->fetchAll();
      foreach ($records as $record) {
        $unc = unserialize($record->var_string);
        kint($unc);
      }
      
      
      return array(
        '#type' => 'markup',
        '#markup' => 'Debug save print page.',
      );
      
  }
  
}