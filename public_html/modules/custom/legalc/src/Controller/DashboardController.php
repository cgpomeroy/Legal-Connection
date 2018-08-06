<?php

namespace Drupal\legalc\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\legalc\Custom;

class DashboardController extends ControllerBase {

  /**
   * Display the markup.
   *
   * @return array
   */
  public function dashboard() {
      
      //print_r($mid);
      
    //////return array(
    /////  '#type' => 'markup',
    /////  '#markup' => $this->t('Dashboard will be here......'),
    /////);
      
      
      /////$renderable = [
       ///// '#theme' => 'my_template',
       ///// '#test_var' => 'test variable',
      /////];
      /////$rendered = drupal_render($renderable);
      
      
    //$m = new \Drupal\legalc\Matters();
    //$m = new Custom\Matters();
    //kint($m->test);
    //$m2 = new Custom\Matter();
    //$m2 = new Custom\Matter();
      
    
    
    
    /////$user = Custom\LCUser::getUser();
    //kint($user->user);
    /////$uid = $user->user->id();
      
    $dashBoard = new Custom\Dashboard();
    
    return array(
      '#type' => 'markup',
      '#markup' => $dashBoard->buildDashboard(),
    );
    
  }
  
  public function dashboardMatter($nid){
        $dashBoard = new Custom\Dashboard($nid);
    
        return array(
          '#type' => 'markup',
          '#markup' => $dashBoard->buildDashboard(),
        );
  }
          
  
  
  
}
  