<?php
namespace cloudgrayau\instafeed\controllers;

use craft\helpers\Json;
use cloudgrayau\instafeed\Instafeed;

use Craft;
use craft\web\Controller;
use craft\web\Request;

class UserController extends Controller {

    protected array|bool|int $allowAnonymous = ['index'];

    // Public Methods
    // =========================================================================

    public function actionIndex(): \yii\web\Response {
      $site = (string)Craft::$app->request->getQueryParam('site');
      return $this->asJson(Instafeed::$plugin->instafeedService->getUser(($site) ? $site : ''));
    }
    
}
