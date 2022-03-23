<?php
/**
 * Instafeed plugin for Craft CMS 4.x
 *
 * Instagram feed for CraftCMS supporting multi-site configurations
 *
 * @link      https://cloudgray.com.au/
 * @copyright Copyright (c) 2021 Cloud Gray Pty Ltd
 */

namespace cloudgrayau\instafeed\models;

use cloudgrayau\instafeed\Instafeed;

use Craft;
use craft\base\Model;

/**
 * @author    Cloud Gray Pty Ltd
 * @package   Instafeed
 * @since     1.0.0
 */
class Settings extends Model
{
  // Public Properties
  // =========================================================================

  /**
   * @var string
   */
  public int $cacheDuration = 3600;

  // Public Methods
  // =========================================================================

  /**
   * @inheritdoc
   */
  public function rules(): array
  {
    return [
      ['cacheDuration', 'integer', 'min' => 0],
    ];
  }
}
