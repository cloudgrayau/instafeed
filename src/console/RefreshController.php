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

class RefreshController extends Controller
{
    // Public Methods
    // =========================================================================

    public function actionIndex(String $handle='') {
      if (empty($handle)){
        $result = Instafeed::$plugin->instafeedService->refreshToken('', \Craft::$app->getSites()->primarySite);
      } else {
        $result = Instafeed::$plugin->instafeedService->refreshToken($handle);
      }
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
          echo $site->name,' || ',$result;
          $results = true;
        }
      }
      return $results;
    }
    
<<<<<<< Updated upstream
    public function actionExpiring($timestamp='172800') {
      echo 'here - this is a test commit';
      return true;
=======
    public function actionExpiring($days='7') {
      $timestamp = (86400 * (int)$days);      
      $date = \craft\helpers\DateTimeHelper::toDateTime($_SERVER['REQUEST_TIME']+$timestamp);      
      $results = false;
      $query = (new \craft\db\Query())
          ->select('siteId')
          ->from('instafeed_tokens')
          ->where('token_expiration <= \''.$date->format('Y-m-d H:i:s').'\'')
          ->all();    
      foreach($query as $site){
        $site = \Craft::$app->getSites()->getSiteById($site['siteId']);
        if ($site){
          $result = Instafeed::$plugin->instafeedService->refreshToken('', $site);
          if ($result){
            echo $site->name,' || ',$result;
            $results = true;
          }
        }
      }
      return $results;
>>>>>>> Stashed changes
    }
    
}