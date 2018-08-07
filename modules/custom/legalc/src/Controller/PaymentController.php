<?php

namespace Drupal\legalc\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\legalc\Custom;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;


class PaymentController extends ControllerBase {

  //Display invoice details with option to mark as paid
  public function paymentDisplay($nid)
  {
      $payment = new Custom\Payment($nid);
      
      $rendered = $payment->renderPaymentDisplayPage();
      
      return array(
        '#type' => 'markup',
        '#markup' => $rendered,
      );
  }
  
}