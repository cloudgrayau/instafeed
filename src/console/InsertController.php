<?php
namespace cloudgrayau\instafeed\console;

use Exception;
use cloudgrayau\instafeed\Instafeed;

use yii\console\Controller;

class InsertController extends Controller {
    
    // Public Methods
    // =========================================================================

    public function actionIndex(String $token='', String $handle=''): void {
      if (empty($handle)){
        echo Instafeed::$plugin->instafeedService->insertToken($token, '', \Craft::$app->getSites()->primarySite);
      } else {
        echo Instafeed::$plugin->instafeedService->insertToken($token, $handle);
      }
    }
    
}