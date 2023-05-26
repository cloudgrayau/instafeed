<?php
namespace cloudgrayau\instafeed\console;

use Exception;
use cloudgrayau\instafeed\Instafeed;

use yii\console\Controller;

class RemoveController extends Controller {
    
    // Public Methods
    // =========================================================================

    public function actionIndex(String $handle=''): void {
      if (empty($handle)){
        echo Instafeed::$plugin->instafeedService->removeToken('', \Craft::$app->getSites()->primarySite);
      } else {
        echo Instafeed::$plugin->instafeedService->removeToken($handle);
      }
    }
    
    public function actionAll(): void {
      $sites = \Craft::$app->sites->getAllSites();
      foreach($sites as $site){
        Instafeed::$plugin->instafeedService->removeToken('', $site);
      }
    }
    
}