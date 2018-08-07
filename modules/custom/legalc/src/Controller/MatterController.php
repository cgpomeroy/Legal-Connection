<?php

namespace Drupal\legalc\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\legalc\Custom;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;


class MatterController extends ControllerBase {

  public function matter($nid)
  {
      
      $matter = new Custom\Matter(null, $nid);
      $matterPage = $matter->renderMatterPage();
      
      
      
      
      
      return array(
        '#type' => 'markup',
        '#markup' => $matterPage,
      );
      
  }
  
  
  //This was going to pull in the matter and tabs for the desktop dashboard via ajax, but not used ATM
  public function matterDesktop($nid)
  {
      
      
      
      $response['data']['html'] = 'Matter and tabs will show here:';
      //$response['data']['test'] = 'some var value';
      $response['method'] = 'POST';
      return new JsonResponse( $response );
  }
  
  public function documents($nid)
  {
      
      $matter = new Custom\Matter(null, $nid);
      $matterDocuments = $matter->matterDocuments();

      
      ////$response['data']['html'] = $matterDocuments;
      $response['data']['html'] = $matterDocuments['html'];
      $response['data']['contextMenu'] = $matterDocuments['contextMenu'];
      $response['method'] = 'POST';
      return new JsonResponse( $response );
      
      
      
      return array(
        '#type' => 'markup',
        '#markup' => 'matter quotes',
      );
  }
  
  public function billing($nid)
  {
      
      ////$matter = new Custom\Matter(null, $nid);
      ////$matterDocuments = $matter->matterDocuments();
      
      $billing = new Custom\Billing($nid, null);
      

      
      ////$response['data']['html'] = $matterDocuments;
      $response['data']['html'] = $billing->matterBillingDisplay();
      $response['data']['contextMenu'] = $billing->getContextMenuData();
      $response['method'] = 'POST';
      return new JsonResponse( $response );
      
      
      
      return array(
        '#type' => 'markup',
        '#markup' => 'matter quotes',
      );
  }
  
  public function account($nid)
  {
      
      ////$matter = new Custom\Matter(null, $nid);
      ////$matterDocuments = $matter->matterDocuments();

      
      ////$response['data']['html'] = $matterDocuments;
      $response['data']['html'] = '<p>Matter account data will be here. Change context menu.</p>';
      //$response['data']['test'] = 'some var value';
      $response['method'] = 'POST';
      return new JsonResponse( $response );
      
      
      
      return array(
        '#type' => 'markup',
        '#markup' => 'matter quotes',
      );
  }
  
  public function activity($nid)
  {
      
      ////$matter = new Custom\Matter(null, $nid);
      ////$matterDocuments = $matter->matterDocuments();

      
      ////$response['data']['html'] = $matterDocuments;
      $response['data']['html'] = '<p>Matter activity data will be here. Change context menu.</p>';
      //$response['data']['test'] = 'some var value';
      $response['method'] = 'POST';
      return new JsonResponse( $response );
      
      
      
      return array(
        '#type' => 'markup',
        '#markup' => 'matter quotes',
      );
  }
  
  
  
  
  public function invoices($nid)
  {
      $matter = new Custom\Matter(null, $nid);
      $matterInvoices = $matter->matterInvoices();
      
      
      
      $response['data']['html'] = $matterInvoices;
      //$response['data']['test'] = 'some var value';
      $response['method'] = 'POST';
      return new JsonResponse( $response );
    
    
      return array(
        '#type' => 'markup',
        '#markup' => 'matter invoices',
      );
  }
  
  public function quotes($nid)
  {
      
      $matter = new Custom\Matter(null, $nid);
      $matterInvoices = $matter->matterQuotes();
      
      
      
      
      
      $response['data']['html'] = $matterInvoices;
      //$response['data']['test'] = 'some var value';
      $response['method'] = 'POST';
      return new JsonResponse( $response );
      
      
      return array(
        '#type' => 'markup',
        '#markup' => 'matter quotes',
      );
  }
  
  
  
  public function payments($nid)
  {
      
      
      $matter = new Custom\Matter(null, $nid);
      $matterPayments = $matter->matterPayments();
      
      
      
      $response['data']['html'] = $matterPayments;
      //$response['data']['test'] = 'some var value';
      $response['method'] = 'POST';
      return new JsonResponse( $response );
      
      
      return array(
        '#type' => 'markup',
        '#markup' => 'matter quotes',
      );
  }
  
  
  //Allow lawyer to invoice quote to client
  public function invoiceClient()
  {
      
      
    
      
    $response['data']['html'] = 'Invoice created and sent to client.';
    $response['data']['test'] = 'some var value';
    $response['method'] = 'POST';
    return new JsonResponse( $response );
  }
  
  
  //Refer matter to a lawyer
  public function referToLawyer($mid, $uid)
  {
   
      
      
        $response['data']['html'] = 'TODO: Refer matter to new lawyer.';
        $response['method'] = 'POST';
        return new JsonResponse( $response );
    
    
  }
  
}