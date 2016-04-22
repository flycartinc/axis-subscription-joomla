<?php
/**
 * @package   Axisubs - Subscription Management System
 * @copyright Copyright (c)2016-2020 Sasi varna kumar / Flycart Technologies
 * @license   GNU General Public License version 3, or later
 */
namespace Flycart\Axisubs\Admin\Model;

defined('_JEXEC') or die;

use FOF30\Container\Container;
use FOF30\Model\DataModel;
use JLoader;

class Currencies extends DataModel {
	public function __construct(Container $container, array $config = array())
    {
        $this->fieldsSkipChecks = [
            'ordering',
        ] ;
        parent::__construct($container, $config);
    }
}