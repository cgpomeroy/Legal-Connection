<?php

namespace Drupal\legalc\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\legalc\Custom;

/**
 * Provides a 'Hello' Block.
 *
 * @Block(
 *   id = "legalc_header_block",
 *   admin_label = @Translation("Legalc header block"),
 *   category = @Translation("Header Block"),
 * )
 */
class HeaderBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
      
        $url = \Drupal\Core\Url::fromRoute('<current>')->toString();
        //kint($url);
        
        $markup = "";
        
        if($url == '/node/add/matter'){
            
            $banner = new Custom\Banner(true, 'Create Matter', true, 'matters');
            //$banner->SetPlusButton('/node/add/matter');
            //$banner->SetSearch(new Custom\Search('matters'));
            $b = $banner->renderBanner();
        
            $markup = $b;
            print_r($b);
            
        }
        
        return array(
                '#markup' => $markup,
            );
        
        /*
        if($markup != ""){
            return array(
                '#markup' => $markup,
            );
        }
        else {
            return false;
        }
        */
        
  }

}