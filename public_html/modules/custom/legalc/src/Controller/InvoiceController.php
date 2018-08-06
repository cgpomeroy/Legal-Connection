<?php

namespace Drupal\legalc\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\legalc\Custom;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;


class InvoiceController extends ControllerBase {

  //Display invoice details with option to mark as paid
  public function invoiceDisplay($nid)
  {
      $invoice = new Custom\Invoice($nid);
      
      $rendered = $invoice->renderInvoiceDisplayPage();
      
      return array(
        '#type' => 'markup',
        '#markup' => $rendered,
      );
  }
  
}