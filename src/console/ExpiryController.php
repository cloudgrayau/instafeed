<?php
namespace cloudgrayau\instafeed\console;

use Exception;
use cloudgrayau\instafeed\Instafeed;

use yii\console\Controller;

class ExpiryController extends Controller {
    
    // Public Methods
    // =========================================================================

    public function actionIndex(String $handle=''): void {
      if (empty($handle)){
        echo Instafeed::$plugin->instafeedService->getTokenExpiration('', \Craft::$app->getSites()->primarySite);
      } else {
        echo Instafeed::$plugin->instafeedService->getTokenExpiration($handle);
      }
    }
    
    public function actionAll(): void {
      $sites = \Craft::$app->sites->getAllSites();
      foreach($sites as $site){
        $result = Instafeed::$plugin->instafeedService->getTokenExpiration('', $site);
        if (!empty($result)){
          echo $site->name,' || ',$result;
        }
      }
    }
    
}