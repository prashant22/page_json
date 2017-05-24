<?php

namespace Drupal\custom_axelerant\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\node\Entity\Node;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * An example controller for showing node content in json format.
 */
class ExampleController extends ControllerBase {

  /**
   * {@inheritdoc}
   */
  public function content($siteAPI, $nid) {
    
    $access = FALSE;
  
    // check for siteapikey or node nid
    if ($siteAPI != NULL && $nid != NULL && is_numeric($nid)) {
    
    // get value of siteapikey Variable
    $config = \Drupal::config('custom_axelerant.settings');
    $siteapikey = $config->get('siteapikey');
    
    // for only where siteapi match
      if ($siteAPI == $siteapikey && $siteapikey != '' && $siteapikey != NULL ) {
        
        // load node from node nid
        $node = Node::load($nid);
        if ($node) {
          $type_name = $node->type->entity->label();
          
          // Should be 'Page' content type only.
          if ( $type_name == 'Basic page' ) {
            
            // node object date to json
            $serializer = \Drupal::service('serializer');
            $data = $serializer->serialize($node, 'json', ['plugin_id' => 'entity']);
            return array(
              '#markup' => $data,
            );
          }	
        }	
      }
    }

    // 'Access Denied'.
    if ($access == FALSE) {
      throw new \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException();
    }
  }

}