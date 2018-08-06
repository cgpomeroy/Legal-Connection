<?php

namespace Drupal\legalc\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\legalc\Custom;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;


class QuoteController extends ControllerBase {

  public function quote($nid)
  {
      //TODO: CHECK ACCESS TO DO THIS
      
      
      $quote = new Custom\Quote($nid);
      
      
      
      $rendered = $quote->renderQuotePage();
      
      return array(
        '#type' => 'markup',
        '#markup' => $rendered,
      );
      
  }
  
  
  //Mark quote advance collected
  public function advanceCollected($nid)
  {
      //TODO: CHECK ACCESS TO DO THIS
      
      return array(
        '#type' => 'markup',
        '#markup' => 'Mark advance collected',
      );
  }
  
  //Mark quote sent to client
  //Send quote to client
  public function sendToClient($nid)
  {
      //TODO: CHECK ACCESS TO DO THIS
      
      kint_require();
      \Kint::$maxLevels = 5;
            
      $node = \Drupal\node\Entity\Node::load($nid);
      //kint($node);
      //$sent = $node->get('field_send_to_client')->getValue();
      //kint($sent[0]['value']);
      //$sentTime = $node->get('field_send_to_client_time')->getValue();
      //kint($sentTime);
      
      $node->set('field_send_to_client', 1);
      $node->set('field_send_to_client_time', time());
      $node->save();
      
      
      return array(
        '#type' => 'markup',
        '#markup' => 'Send quote to client',
      );
  }
  
  //Send invoice to client
  //Mark quote as invoiced
  public function invoice($nid)
  {
      //TODO: CHECK ACCESS TO DO THIS
      
      return array(
        '#type' => 'markup',
        '#markup' => 'Invoice quote to client',
      );
  }
  
  //Mark quote as paid
  public function paid($nid)
  {
      //TODO: CHECK ACCESS TO DO THIS
      
      
      return array(
        '#type' => 'markup',
        '#markup' => 'Mark quote paid',
      );
  }
  
  //Client accepts the quote
  public function clientConfirmed($nid)
  {
      //TODO: CHECK ACCESS TO DO THIS
      
      
      
      return array(
        '#type' => 'markup',
        '#markup' => 'Client accepted the quote',
      );
  }
  
  
  
  
}