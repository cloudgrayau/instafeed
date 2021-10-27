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

class FeedController extends Controller
{

    // Protected Properties
    // =========================================================================

    protected $allowAnonymous = ['index'];

    // Public Methods
    // =========================================================================

    public function actionIndex(): \yii\web\Response
    {
        $limit = (int)Craft::$app->request->getQueryParam('limit');
        $site = (string)Craft::$app->request->getQueryParam('site');
        return $this->asJson(Instafeed::$plugin->instafeedService->getFeed(($limit) ? $limit : 10, ($site) ? $site : ''));
    }
}
