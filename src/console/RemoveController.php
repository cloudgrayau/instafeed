<?php
/**
 * Instafeed plugin for Craft CMS 4.x
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

class RemoveController extends Controller
{
    // Public Methods
    // =========================================================================

    public function actionIndex(String $handle='') {
      if (empty($handle)){
        echo Instafeed::$plugin->instafeedService->removeToken('', \Craft::$app->getSites()->primarySite);
      } else {
        echo Instafeed::$plugin->instafeedService->removeToken($handle);
      }
    }
    
    public function actionAll() {
      $sites = \Craft::$app->sites->getAllSites();
      foreach($sites as $site){
        Instafeed::$plugin->instafeedService->removeToken('', $site);
      }
    }
    
}