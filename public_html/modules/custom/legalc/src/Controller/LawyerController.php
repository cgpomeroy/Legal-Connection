<?php

namespace Drupal\legalc\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\legalc\Custom;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;


class LawyerController extends ControllerBase {

  public function profile($uid)
  {
      
      $user = Custom\Lawyer::getUser();
      
      $lawyerProfilePage = $user->lawyerProfile();
      
      
      
      return array(
        '#type' => 'markup',
        '#markup' => $lawyerProfilePage,
      );
      
  }
  
  public function workDetails($uid)
  {
      
      $user = Custom\Lawyer::getUser();
      $lawyerWorkDetails = $user->workDetails();
      
      /*
      return array(
        '#type' => 'markup',
        '#markup' => $lawyerWorkDetails,
      );
      */      
            
      
      
      
      $response['data']['html'] = $lawyerWorkDetails;
      //$response['data']['test'] = 'some var value';
      $response['method'] = 'POST';
      return new JsonResponse( $response );
  }
  
  public function circulars($uid)
  {
      
      
      $response['data']['html'] = 'Lawyer profile circulars';
      //$response['data']['test'] = 'some var value';
      $response['method'] = 'POST';
      return new JsonResponse( $response );
  }
  
  public function reviews($uid)
  {
      
      
      $response['data']['html'] = 'Lawyer profile reviews';
      //$response['data']['test'] = 'some var value';
      $response['method'] = 'POST';
      return new JsonResponse( $response );
  }
  
  public function faqs($uid)
  {
      
      
      $response['data']['html'] = 'Lawyer profile faqs';
      //$response['data']['test'] = 'some var value';
      $response['method'] = 'POST';
      return new JsonResponse( $response );
  }
  
  public function newsFeed($uid)
  {
      
      
      $response['data']['html'] = 'Lawyer profile newsFeed';
      //$response['data']['test'] = 'some var value';
      $response['method'] = 'POST';
      return new JsonResponse( $response );
  }
  
  
  
}