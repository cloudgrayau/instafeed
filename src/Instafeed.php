<?php
/**
 * Instafeed plugin for Craft CMS 3.x
 *
 * Instagram feed for CraftCMS supporting multi-site configurations
 *
 * @link      https://cloudgray.com.au/
 * @copyright Copyright (c) 2021 Cloud Gray Pty Ltd
 */

namespace cloudgrayau\instafeed;

use cloudgrayau\instafeed\models\Settings;
use cloudgrayau\instafeed\variables\InstafeedVariable;

use Craft;
use craft\web\twig\variables\CraftVariable;
use craft\base\Plugin;
use craft\services\Plugins;
use craft\console\Application as ConsoleApplication;

use yii\base\Event;

/**
 * Class Instafeed
 *
 * @author    Cloud Gray Pty Ltd
 * @package   Instafeed
 * @since     1.0.0
 *
 */
class Instafeed extends Plugin
{
    // Static Properties
    // =========================================================================

    /**
     * @var Instafeed
     */
    public static $plugin;

    // Public Properties
    // =========================================================================

    /**
     * @var string
     */
    public $schemaVersion = '1.0.0';

    /**
     * @var bool
     */
    public $hasCpSettings = true;

    /**
     * @var bool
     */
    public $hasCpSection = false;

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        self::$plugin = $this;
        
        if (Craft::$app instanceof ConsoleApplication) {
            $this->controllerNamespace = 'cloudgrayau\instafeed\console';
        }
        
        Event::on(CraftVariable::class, CraftVariable::EVENT_INIT, function (Event $event) {
          $event->sender->set('instafeed', InstafeedVariable::class);
        });
        
        /*Event::on(Site::class, Site::EVENT_AFTER_DELETE_SITE, function(DeleteSiteEvent $event) {
        });*/
    }

  // Protected Methods
  // =========================================================================

  /**
   * @inheritdoc
   */
  protected function createSettingsModel(){
    return new Settings();
  }
  
  protected function settingsHtml(): string {
    return Craft::$app->view->renderTemplate(
      'instafeed/settings',
      [
        'settings' => $this->getSettings()
      ]
    );
  }

}
