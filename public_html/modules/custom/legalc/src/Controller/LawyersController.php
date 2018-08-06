<?php

namespace Drupal\legalc\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\legalc\Custom;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;


class LawyersController extends ControllerBase {

  public function listLawyers($nid, $action)
  {
      
      $lawyers = new Custom\Lawyers();
      $lawyersPage = $lawyers->listLawyersPage($nid, $action);
      
      
      
      return array(
        '#type' => 'markup',
        '#markup' => $lawyersPage,
      );
      
  }
  
  
  
  public function listAll($nid, $action)
  {
      
      $lawyers = new Custom\Lawyers();
      $allLawyers = $lawyers->listAllLawyers($nid, $action);
      
      
      /*
      return array(
        '#type' => 'markup',
        '#markup' => $allLawyers,
      );
      */
      
      
      $response['data']['html'] = $allLawyers;
      //$response['data']['test'] = 'some var value';
      $response['method'] = 'POST';
      return new JsonResponse( $response );
  }
  
  
  public function listNetwork($nid, $action)
  {
      
      
      $response['data']['html'] = 'list lawyers in network here.';
      //$response['data']['test'] = 'some var value';
      $response['method'] = 'POST';
      return new JsonResponse( $response );
  }
  
  
  
  
}