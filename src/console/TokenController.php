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
use Psr\Http\Message\StreamInterface;
use yii\console\Controller;

class TokenController extends Controller
{
    // Public Methods
    // =========================================================================

    public function actionGet(String $handle='') {
      Instafeed::$plugin->instafeedService->dumpToken($handle);
    }

    public function actionInsert(String $token='', String $handle='') {
      Instafeed::$plugin->instafeedService->insertToken($token, $handle);
    }
    
    public function actionRemove(String $handle='') {
      Instafeed::$plugin->instafeedService->removeToken($handle);
    }

    public function actionRefresh(String $handle='') {
      Instafeed::$plugin->instafeedService->refreshToken($handle);
    }

    public function actionExp(String $handle='') {
      Instafeed::$plugin->instafeedService->getTokenExpiration($handle);
    }
    
}
