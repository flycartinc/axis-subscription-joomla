<?php
/**
 * @package   Axisubs - Subscription Management System
 * @copyright Copyright (c)2016-2020 Sasi varna kumar / Flycart Technologies
 * @license   GNU General Public License version 3, or later
 */
// No direct access to this file
defined ( '_JEXEC' ) or die ();

include_once JPATH_LIBRARIES . '/fof30/include.php';

use \FOF30\Inflector\Inflector;
use Flycart\Axisubs\Admin\Helper\Router;

function AxisubsBuildRoute(&$query) {

	$router = new AxisubsRouter();
	return $router->build($query);
}

function AxisubsParseRoute($segments) {
	$router = new AxisubsRouter();
	return $router->parse($segments);
}

require_once(JPATH_ROOT.'/administrator/components/com_axisubs/Helper/Router.php');

class AxisubsRouter extends JComponentRouterBase {
	
	public function build(&$query) {
		$segments = array ();
		// If there is only the option and Itemid, let Joomla! decide on the naming scheme
		if (isset ( $query ['option'] ) && isset ( $query ['Itemid'] ) && ! isset ( $query ['view'] ) && ! isset ( $query ['task'] ) && ! isset ( $query ['layout'] ) && ! isset ( $query ['id'] )) {
			return $segments;
		}

		$menus = JMenu::getInstance ( 'site' );

		$view = Router::getAndPop ( $query, 'view', 'carts' );
		$task = Router::getAndPop ( $query, 'task' );
		$layout = Router::getAndPop ( $query, 'layout' );
		$id = Router::getAndPop ( $query, 'id' );
		$Itemid = Router::getAndPop ( $query, 'Itemid' );
		// $orderpayment_type = AxisubsRouterHelper::getAndPop($query, 'orderpayment_type');
		// $paction = AxisubsRouterHelper::getAndPop($query, 'paction');
		$qoptions = array (
				'option' => 'com_axisubs',
				'view' => $view,
				'task' => $task,
				'id' => $id
		);

		switch ($view) {
			case 'Login' :
			case 'login' :
				// Is it a mycart menu?
				if ($Itemid) {
					$menu = $menus->getItem ( $Itemid );
					$mView = isset ( $menu->query ['view'] ) ? $menu->query ['view'] : 'login';
					$mTask = isset ( $menu->query ['task'] ) ? $menu->query ['task'] : '';
					// No, we have to find another root
					if (($mView != 'Login' && $mView != 'login'))
						$Itemid = null;
				}

				if (empty ( $Itemid )) {
					// No menu found, let's add a segment manually
					$segments [] = 'login';
					if (isset ( $task )) {
						$segments [] = $task;
					}
				} else {

				// sometimes we need task
				//	$segments [] = 'carts';
					if (isset ( $mTask ) && ! empty ( $mTask )) {
						$segments [] = $mTask;
					} elseif (isset ( $task )) {
						$segments [] = $task;
					}
					// Joomla! will let the menu item naming work its magic
					$query ['Itemid'] = $Itemid;
				}
				break;
		}

		return $segments;
	}


	public function parse(&$segments) {
		//var_dump($segments);
		$query = array ();
		$menus = JMenu::getInstance ( 'site' );
		$menu = $menus->getActive ();
		$vars = array ();
		$total = count ( $segments );
		for($i = 0; $i < $total; $i ++) {
			$segments [$i] = preg_replace ( '/-/', ':', $segments [$i], 1 );
		}

		if (is_null ( $menu ) && count ( $segments )) {
			if ($segments [0] == 'Login' || $segments [0] == 'login') {
				$vars ['view'] = $segments [0];
				if (isset ( $segments [1] )) {
					$vars ['task'] = $segments [1];
				}
			}
		} else {
			if (count ( $segments )) {

				$mView = $menu->query ['view'];

				if (isset ( $mView ) && ($mView == 'login' || $mView == 'Login')) {
					$vars ['view'] = $mView;
					if (isset ( $segments [0] )) {
						$vars ['task'] = $segments [0];
					}

				} elseif ($segments [0] == 'login' || $segments [0] == 'Login') {
					$vars ['view'] = $segments [0];
					if (isset ( $segments [1] )) {
						$vars ['task'] = $segments [1];
					}
				}

			}
		}
		return $vars;
	}

}
