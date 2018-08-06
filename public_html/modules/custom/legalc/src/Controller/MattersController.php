<?php

namespace Drupal\legalc\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\legalc\Custom;



use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;


class MattersController extends ControllerBase {

  /**
   * Display the markup.
   *
   * @return array
   */
  public function matters() {
      
    \Drupal::service('page_cache_kill_switch')->trigger();
    
    /////$user = Custom\LCUser::getUser();
    //kint($user->user);
    //$uid = $user->user->id();
    
    $matters = new Custom\Matters();
    $mattersPage = $matters->BuildMattersPage();
    
    $deviceState = Custom\MyMobileDetect::detectState();
    kint($deviceState);
    
    return array(
      '#type' => 'markup',
      '#markup' => $mattersPage,
    );
    
  }
  
  
  /*
  public function matter($nid)
  {
      
      $matter = new Custom\Matter(null, $nid);
      $matterPage = $matter->renderMatterPage();
      
      
      
      
      
      return array(
        '#type' => 'markup',
        '#markup' => $matterPage,
      );
      
  }
  */
  
  public function allMatters()
  {
      
    //$user = Custom\LCUser::getUser();
    $matters = new Custom\Matters();
    $allMatters = $matters->getAllMattersUser();
    
    
    //$nids = \Drupal::entityQuery('node')->condition('type','matter')->orderBy('e.created', 'DESC')->execute();
    //$nodes =  \Drupal\node\Entity\Node::loadMultiple($nids);
    //kint($nodes);
    
    
    /*
    return array(
      '#type' => 'markup',
      '#markup' => $allMatters,
    );
    */
    
    $response['data']['html'] = $allMatters;
    $response['data']['contextMenu'] = $matters->getAllMattersContextMenu();
    $response['method'] = 'POST';
    return new JsonResponse( $response );
    

    /*
    return new JsonResponse( $response );
      
      $build = array(
        '#type' => 'markup',
        '#markup' => '<p>All matters page</p>',
      );
      return $build;
      
      return new Response(render($build));
    */
    
    
  }
  
  public function referralMatters()
  {
        $response['data']['html'] = 'Referred matters will list here.';
        $response['data']['test'] = 'some var value';
        $response['method'] = 'POST';
        return new JsonResponse( $response );
  }
  
  
  public function starredMatters()
  {
   
    $matters = new Custom\Matters();
    $starredMatters = $matters->getAllStarredMattersUser();
    
    $response['data']['html'] = $starredMatters;
    $response['data']['test'] = 'some var value';
    $response['method'] = 'POST';
    return new JsonResponse( $response );
    
    
    
    /*
      $build = array(
        '#type' => 'markup',
        '#markup' => '<p>Starred matters page</p>',
      );
      return new Response(render($build));
    */  
      
  }
  
  public function archivedMatters()
  {
    $matters = new Custom\Matters();
    $archivedMatters = $matters->getAllArchivedMattersUser();
    
    $response['data']['html'] = $archivedMatters;
    $response['data']['test'] = 'some var value';
    $response['method'] = 'POST';
    return new JsonResponse( $response );
      
    /*
      $build = array(
        '#type' => 'markup',
        '#markup' => '<p>Archived matters page</p>',
      );
      return new Response(render($build));
     
     */
    
    
  }
  
  
  
  
  
  
  
}