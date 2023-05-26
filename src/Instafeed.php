<?php
namespace cloudgrayau\instafeed;

use cloudgrayau\instafeed\models\Settings;
use cloudgrayau\instafeed\variables\InstafeedVariable;
use cloudgrayau\utils\UtilityHelper;

use Craft;
use craft\web\twig\variables\CraftVariable;
use craft\base\Plugin;
use craft\services\Plugins;
use craft\console\Application as ConsoleApplication;

use yii\base\Event;

class Instafeed extends Plugin {

  public static $plugin;
  public string $schemaVersion = '1.0.0';
  public bool $hasCpSettings = true;
  public bool $hasCpSection = true;
  
  // Public Methods
  // =========================================================================
  
  public function init(): void {
    parent::init();
    self::$plugin = $this;
    $this->_registerComponents();  
    $this->_registerConsole();
    $this->_registerVariables();
  }
  
  // Private Methods
  // =========================================================================
  
  private function _registerComponents(): void {
    UtilityHelper::registerModule();
  }
  
  private function _registerConsole(): void {
    if (Craft::$app instanceof ConsoleApplication) {
      $this->controllerNamespace = 'cloudgrayau\instafeed\console';
    }
  }
  
  private function _registerVariables(): void {
    Event::on(CraftVariable::class, CraftVariable::EVENT_INIT, function (Event $event) {
      $event->sender->set('instafeed', InstafeedVariable::class);
    });
  }
  
  // Protected Methods
  // =========================================================================
  
  protected function createSettingsModel(): ?\craft\base\Model{
    return new Settings();
  }
  
  protected function settingsHtml(): ?string {
    return Craft::$app->view->renderTemplate(
      'instafeed/settings',
      [
        'settings' => $this->getSettings()
      ]
    );
  }

}
