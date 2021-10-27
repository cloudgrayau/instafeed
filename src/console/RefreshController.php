<?php
/**
 * Instafeed plugin for Craft CMS 3.x
 *
 * Instagram feed for CraftCMS supporting multi-site configurations
 *
 * @link      https://cloudgray.com.au/
 * @copyright Copyright (c) 2021 Cloud Gray Pty Ltd
 */

namespace cloudgrayau\instafeed\console;

use Exception;
use cloudgrayau\instafeed\Instafeed;

use yii\console\Controller;

class RefreshController extends Controller
{
    // Public Methods
    // =========================================================================

    public function actionIndex(String $handle='') {
      $result = Instafeed::$plugin->instafeedService->refreshToken($handle);
      if ($result){
        echo $result;
        return true;
      }
      return false;
    }
    
    public function actionAll() {
      $results = false;
      $sites = \Craft::$app->sites->getAllSites();
      foreach($sites as $site){
        $result = Instafeed::$plugin->instafeedService->refreshToken('', $site);
        if (!empty($result)){
          echo '[ ',$site->name,' ] ',$result;
          $results = true;
        }
      }
      return $results;
    }
    
    public function actionExpiring($timestamp='172800') {
      echo 'here';
      return true;
    }
    
}