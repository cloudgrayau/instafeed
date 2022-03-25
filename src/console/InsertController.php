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

class InsertController extends Controller
{
    // Public Methods
    // =========================================================================

    public function actionIndex(String $token='', String $handle='') {
      if (empty($handle)){
        echo Instafeed::$plugin->instafeedService->insertToken($token, '', \Craft::$app->getSites()->primarySite);
      } else {
        echo Instafeed::$plugin->instafeedService->insertToken($token, $handle);
      }
    }
    
}