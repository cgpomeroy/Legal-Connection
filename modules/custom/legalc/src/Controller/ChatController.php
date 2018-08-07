<?php

namespace Drupal\legalc\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\legalc\Custom;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class ChatController extends ControllerBase {

  /**
   * Display the markup.
   *
   * @return array
   */
  public function chat() {
      
    //print_r($mid);
    ///chat?matter=69
      
      
    $chat = new Custom\Chat((int)$_GET['matter']);
    $html = $chat->renderStart();
    
    
    
    $response['data']['html'] = $html;
    $response['data']['contextMenu'] = $chat->getContextMenuValue();
    $response['method'] = 'POST';
    return new JsonResponse( $response );
    
      
    return array(
      '#type' => 'markup',
      '#markup' => $html,
    );
  }
  
  
  /*
   * Receive message from chat app
   */
  public function message()
  {
      $chat = new Custom\Chat((int)$_POST['_mid']);
      $t = $chat->newMessage($_POST['_msg']);
      
      
      
      
      //$response['data']['html'] = 'Test of chat html response';
      $response['data']['mid'] = (int)$_POST['_mid'];
      $response['data']['msg'] = $_POST['_msg'];
      //$response['data']['roles'] = $t['roles'];
      $response['method'] = 'POST';
      return new JsonResponse( $response );
      
  }
  
  /*
   * Fetch previous messages in chat
   */
  public function archive()
  {
      
      //$chat = new Custom\Chat((int)$_POST['_mid']);
      //$t = $chat->newMessage($_POST['_msg']);
      
      $mid = (int)$_GET['matter'];
      
      $chat = new Custom\Chat($mid);
      $messages = $chat->getChatOlderMessages();
      
      
      
      //return array(
      //  '#type' => 'markup',
      //  '#markup' => 'chat archive',
      //);
      
      
      //$response['data']['html'] = 'Test of chat html response';
      //$response['data']['mid'] = (int)$_POST['_mid'];
      //$response['data']['msg'] = $_POST['_msg'];
      $response['data'] = $messages;
      $response['method'] = 'POST';
      
      return new JsonResponse( $response );
      
  }

}