<?php
/**
 * @package   Axisubs - Subscription Management System
 * @copyright Copyright (c)2016-2020 Sasi varna kumar / Flycart Technologies
 * @license   GNU General Public License version 3, or later
 */

namespace Flycart\Axisubs\Admin\Model;

defined('_JEXEC') or die;

use FOF30\Model\DataModel;
use FOF30\Container\Container;

class Taxes extends DataModel
{
    public function __construct(Container $container, array $config = array())
    {
        $this->tableName = "#__axisubs_taxrates";
        $this->idFieldName = "axisubs_taxrate_id";
        $this->fieldsSkipChecks = [ 
            'params',
            'tax_rate_country',
            'tax_rate_state',
            'tax_rate',
            'tax_rate_name',
            'tax_rate_priority',
            'tax_rate_compound',
            'tax_rate_shipping',
            'tax_rate_order',
        ] ;
        parent::__construct($container, $config);
    }
}