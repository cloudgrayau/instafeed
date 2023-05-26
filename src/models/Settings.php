<?php
namespace cloudgrayau\instafeed\models;

use cloudgrayau\instafeed\Instafeed;

use Craft;
use craft\base\Model;

class Settings extends Model {
  
  public int $cacheDuration = 3600;

  // Public Methods
  // =========================================================================

  public function rules(): array {
    return [
      ['cacheDuration', 'integer', 'min' => 0],
    ];
  }
  
}
