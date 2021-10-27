<?php
/**
 * Instafeed plugin for Craft CMS 3.x
 *
 * Instagram feed for CraftCMS supporting multi-site configurations
 *
 * @link      https://cloudgray.com.au/
 * @copyright Copyright (c) 2021 Cloud Gray Pty Ltd
 */

namespace cloudgrayau\instafeed\controllers;

use craft\helpers\Json;
use cloudgrayau\instafeed\Instafeed;

use Craft;
use craft\web\Controller;
use craft\web\Request;

class UserController extends Controller
{

    // Protected Properties
    // =========================================================================

    protected $allowAnonymous = ['index'];

    // Public Methods
    // =========================================================================

    public function actionIndex(): \yii\web\Response
    {
      $site = (string)Craft::$app->request->getQueryParam('site');
      return $this->asJson(Instafeed::$plugin->instafeedService->getUser(($site) ? $site : ''));
    }
}
