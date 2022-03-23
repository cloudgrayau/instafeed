<?php
/**
 * Instafeed plugin for Craft CMS 4.x
 *
 * Instagram feed for CraftCMS supporting multi-site configurations
 *
 * @link      https://cloudgray.com.au/
 * @copyright Copyright (c) 2021 Cloud Gray Pty Ltd
 */

namespace cloudgrayau\instafeed\variables;

use cloudgrayau\instafeed\Instafeed;

class InstafeedVariable {
  
    public function getUser(String $site='') {
      return Instafeed::$plugin->instafeedService->getUser($site);
    }
    public function getFeed($limit=10, String $site='') {
      return Instafeed::$plugin->instafeedService->getFeed(((int)$limit > 0) ? (int)$limit : 10, $site);
    }
    
}
