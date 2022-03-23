<?php
/**
 * Instafeed plugin for Craft CMS 4.x
 *
 * Instagram feed for CraftCMS supporting multi-site configurations
 *
 * @link      https://cloudgray.com.au/
 * @copyright Copyright (c) 2021 Cloud Gray Pty Ltd
 */

namespace cloudgrayau\instafeed\services;

use cloudgrayau\instafeed\Instafeed;
use Craft;
use DateTime;
use craft\helpers\DateTimeHelper;
use craft\base\Component;
use craft\db\Query;
use craft\helpers\App;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use yii\db\Exception;

class InstafeedService extends Component
{
  
    private array $settings = [
      'api' => 'https://graph.instagram.com/',
      'me_endpoint' => 'me',
      'media_endpoint' => 'me/media',
      'refresh_endpoint' => 'refresh_access_token',
      'fields' => 'caption,media_type,media_url,permalink,timestamp,username,children{media_type,media_url}'
    ];
    
    // Public Methods
    // =========================================================================

    public function dumpToken(String $handle, \craft\models\Site $model=null)
    {
      $site = ($model) ? $model : $this->_getSite($handle);
      if ($site){
        $token = $this->_getAccessToken($site->id);
        if (!empty($token)){
          return $token."\n";
        }
      }
      return false;
    }

    public function insertToken(String $token, String $handle, \craft\models\Site $model=null)
    {
      $site = ($model) ? $model : $this->_getSite($handle);
      if ((!empty($token)) && ($site)){
        $this->_remove($site->id);
        $results = $this->_refresh($token);
        if ($results){
          try {
            (new Query())
              ->createCommand()
              ->insert('instafeed_tokens', [
                'siteId' => $site->id,
                'access_token' => $results['token'],
                'token_expiration' => $results['expires']
              ])->execute();
            $exp_date = DateTimeHelper::toDateTime($results['expires'])->format('F d, Y \a\t H:i:s A.');
            $target = DateTimeHelper::toDateTime($results['expires']);
            $interval = (new DateTime())->diff($target)->format('%a day(s)');
            return 'Expires in '.$interval.' on '.$exp_date."\n";
          } catch (Exception $e) {
            return false;
          }
        }
      }
    }
    
    public function removeToken(String $handle, \craft\models\Site $model=null)
    {
      $site = ($model) ? $model : $this->_getSite($handle);
      if ($site){
        $this->_remove($site->id);
        return true;
      }
      return false;
    }

    public function refreshToken(String $handle, \craft\models\Site $model=null)
    {
      $site = ($model) ? $model : $this->_getSite($handle);
      if ($site){
        $result = (new Query())
          ->select(['id','access_token'])
          ->from('instafeed_tokens')
          ->where(['siteId' => $site->id])
          ->one();
        if (isset($result['id'])){
          $results = $this->_refresh($result['access_token']);
          if ($results){
            try {
              (new Query())
                ->createCommand()
                ->update('instafeed_tokens', [
                  'access_token' => $results['token'],
                  'token_expiration' => $results['expires']
                ], ['id' => $result['id']])->execute();
              $exp_date = DateTimeHelper::toDateTime($results['expires'])->format('F d, Y \a\t H:i:s A.');
              $target = DateTimeHelper::toDateTime($results['expires']);
              $interval = (new DateTime())->diff($target)->format('%a day(s)');
              return 'Expires in '.$interval.' on '.$exp_date."\n";
            } catch (Exception $e) {
              return false;
            }
          } else {
            \Craft::$app->db->createCommand()
              ->delete('instafeed_tokens', ['id' => $result['id']])
              ->execute();
            return false;
          }
        }
      }
      return false;
    }

    public function getTokenExpiration(String $handle, \craft\models\Site $model=null)
    {
      $site = ($model) ? $model : $this->_getSite($handle);
      if ($site){
        $tokenage = $this->_getAccessTokenAge($site->id);
        if (!empty($tokenage)){
          $exp_date = DateTimeHelper::toDateTime($tokenage)->format('F d, Y \a\t H:i:s A.');
          $target = DateTimeHelper::toDateTime($tokenage);
          $interval = (new DateTime())->diff($target)->format('%a day(s)');
          return 'Expires in '.$interval.' on '.$exp_date."\n";
        }
      }
      return false;
    }
    
    public function getUser(String $handle='')
    {
      $site = $this->_getSite($handle);
      if ($site){
        $token = $this->_getAccessToken($site->id);
        if ($token){
          $cache = Craft::$app->getCache();
          $settings = $this->settings;
          $settings_ = Instafeed::$plugin->getSettings();
          return $cache->getOrSet('instafeed_'.$site->handle.'_user', function() use ($settings, $token) {
            $client = new Client([
              'base_uri' => $this->settings['api'],
            ]);
            try {
              $response = $client->request('GET', $settings['me_endpoint'], [
                'query' => [
                  'fields' => 'id,account_type,media_count,username',
                  'access_token' => $token
                ]
              ]);
              return json_decode($response->getBody()->getContents(), true);
            } catch (GuzzleException $e) {
              return $e->getMessage();
            }
          }, $settings_['cacheDuration']);
        }
      }
      return;
    }

    public function getFeed($limit=10, String $handle='')
    {
      $site = $this->_getSite($handle);
      if ($site){
        $token = $this->_getAccessToken($site->id);
        if ($token){
          $cache = Craft::$app->getCache();
          $settings = $this->settings;
          $settings_ = Instafeed::$plugin->getSettings();
          return $cache->getOrSet('instafeed_'.$site->handle.'_'.$limit, function() use ($settings, $token, $limit) {
            $client = new Client([
              'base_uri' => $this->settings['api'],
            ]);
            try {
              $response = $client->request('GET', $settings['media_endpoint'], [
                'query' => [
                  'fields' => $settings['fields'],
                  'access_token' => $token,
                  'limit'=>$limit
                ]
              ]);
              return json_decode($response->getBody()->getContents(), true)['data'];
            } catch (GuzzleException $e) {
              return $e->getMessage();
            }
          }, $settings_['cacheDuration']);
        }
      }
      return;
    }
    
    // Private Methods
    // =========================================================================
    
    private function _getSite($handle){
      if (!empty($handle)){
        $site = Craft::$app->getSites()->getSiteByHandle($handle);
      } else {
        $site = Craft::$app->getSites()->currentSite;
      }
      return $site;
    }
    
    private function _refresh($token){
      $client = new Client([
        'base_uri' => $this->settings['api'],
      ]);
      try {
        $response = $client->request('GET', $this->settings['refresh_endpoint'], [
          'query' => [
            'grant_type' => 'ig_refresh_token',
            'access_token' => $token
          ]
        ]);
        $results = json_decode($response->getBody());
        if (isset($results->access_token)){
          return [
            'token' => $results->access_token,
            'expires' => (new Datetime())->add(DateTimeHelper::secondsToInterval($results->expires_in))->format('Y-m-d H:i:s')
          ];  
        }
      } catch (GuzzleException $e) {
        return false;
      }
    }
    
    private function _remove($siteId){
      \Craft::$app->db->createCommand()
        ->delete('instafeed_tokens', ['siteId' => $siteId])
        ->execute();
    }

    private function _getAccessToken($siteId){
      $token = (new Query())
        ->select(['access_token'])
        ->from('instafeed_tokens')
        ->where(['siteId' => $siteId])
        ->one();
      return ($token['access_token'] ?? null);
    }

    private function _getAccessTokenAge($siteId){
      $token = (new Query())
        ->select(['token_expiration'])
        ->from('instafeed_tokens')
         ->where(['siteId' => $siteId])
        ->one();
      return ($token['token_expiration'] ?? null);
    }
}
