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

		$view = Router::getAndPop ( $query, 'view', 'Plans' );
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
			case 'Plans' :
			case 'plans' :
				// Is it a mycart menu?
				if ($Itemid) {
					$menu = $menus->getItem ( $Itemid );
					$mView = isset ( $menu->query ['view'] ) ? $menu->query ['view'] : 'plans';
					$mTask = isset ( $menu->query ['task'] ) ? $menu->query ['task'] : '';
					// No, we have to find another root
					if (($mView != 'Plans' && $mView != 'plans'))
						$Itemid = null;
				}

				if (empty ( $Itemid )) {
					// No menu found, let's add a segment manually
					$segments [] = 'plans';
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
			case 'Plan' :
			case 'plan' :
				$plan_slug = Router::getAndPop ( $query, 'slug' );
				// Is it a mycart menu?
				if ($Itemid) {
					$menu = $menus->getItem ( $Itemid );
					$mView = isset ( $menu->query ['view'] ) ? $menu->query ['view'] : 'plan';
					$mTask = isset ( $menu->query ['task'] ) ? $menu->query ['task'] : '';
					// No, we have to find another root
					if (($mView != 'plan' && $mView != 'Plan'))
						$Itemid = null;
				}

				if (empty ( $Itemid )) {
					// No menu found, let's add a segment manually
					$segments [] = 'Plan';
					if (isset ( $task )) {
						$segments [] = $task;
					}
					if (isset ( $plan_slug )) {
						$segments [] = $plan_slug;
					}
					
				} else {

				// sometimes we need task
					if (isset ( $mTask ) && ! empty ( $mTask )) {
						$segments [] = $mTask;
					} elseif (isset ( $task )) {
						$segments [] = $task;
					}
					
					if (isset ( $plan_slug )) {
						$segments [] = $plan_slug;
					}

					// Joomla! will let the menu item naming work its magic
					$query ['Itemid'] = $Itemid;
				}
				break;	
			case 'Subscribe' :
			case 'subscribe' :
				$plan_slug = Router::getAndPop ( $query, 'plan' );
				// Is it a mycart menu?
				if ($Itemid) {
					$menu = $menus->getItem ( $Itemid );
					$mView = isset ( $menu->query ['view'] ) ? $menu->query ['view'] : 'subscribe';
					$mTask = isset ( $menu->query ['task'] ) ? $menu->query ['task'] : '';
					// No, we have to find another root
					if (($mView != 'subscribe' && $mView != 'Subscribe'))
						$Itemid = null;
				}

				if (empty ( $Itemid )) {
					// No menu found, let's add a segment manually
					$segments [] = 'Subscribe';
					if (isset ( $task )) {
						$segments [] = $task;
					}
					if (isset ( $plan_slug )) {
						$segments [] = $plan_slug;
					}
					
				} else {

				// sometimes we need task
					if (isset ( $mTask ) && ! empty ( $mTask )) {
						$segments [] = $mTask;
					} elseif (isset ( $task )) {
						$segments [] = $task;
					}
					
					if (isset ( $plan_slug )) {
						$segments [] = $plan_slug;
					}

					// Joomla! will let the menu item naming work its magic
					$query ['Itemid'] = $Itemid;
				}
				break;		
			case 'Profile' :
			case 'profile' :
				// Is it a mycart menu?
				if ($Itemid) {
					$menu = $menus->getItem ( $Itemid );
					$mView = isset ( $menu->query ['view'] ) ? $menu->query ['view'] : 'profile';
					$mTask = isset ( $menu->query ['task'] ) ? $menu->query ['task'] : '';
					// No, we have to find another root
					if (($mView != 'profile' && $mView != 'Profile'))
						$Itemid = null;
				}

				if (empty ( $Itemid )) {
					// No menu found, let's add a segment manually
					$segments [] = 'profile';
					if (isset ( $task )) {
						$segments [] = $task;
					}
					
				} else {

					// sometimes we need task
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
		/*for($i = 0; $i < $total; $i ++) {
			$segments [$i] = preg_replace ( '/-/', ':', $segments [$i], 1 );
		}*/
		$subs_tasks = array( 'paySubscription', 'subscribeUser','confirmPayment', 'renew' );

		if (is_null ( $menu ) && count ( $segments )) {
			if ($segments [0] == 'Plans' || $segments [0] == 'plans') {
				$vars ['view'] = $segments [0];
				if (isset ( $segments [1] )) {
					$vars ['task'] = $segments [1];
				}
			}

			if ($segments [0] == 'Plan' || $segments [0] == 'plan') {
				$vars ['view'] = $segments [0];
				if (isset ( $segments [1] )) {
					$vars ['slug'] = $segments [1];
				}
			}

			if ($segments [0] == 'Subscribe' || $segments [0] == 'subscribe') {
				$vars ['view'] = $segments [0];
				if (isset ( $segments [1] )) {
					if (in_array($segments [1], $subs_tasks)) {
						$vars ['task'] = $segments [1];	
					}else {
						$vars ['plan'] = $segments [1];	
					}
				}
			}

			if ($segments [0] == 'Profile' || $segments [0] == 'profile') {
				$vars ['view'] = $segments [0];
				if (isset ( $segments [1] )) {
					$vars ['task'] = $segments [1];
				}
			}

		} else {
			if (count ( $segments )) {

				$mView = $menu->query ['view'];

				if (isset ( $mView ) && ($mView == 'plans' || $mView == 'Plans')) {
					$vars ['view'] = $mView;
					if (isset ( $segments [0] )) {
						$vars ['task'] = $segments [0];
					}

				} elseif ($segments [0] == 'plans' || $segments [0] == 'Plans') {
					$vars ['view'] = $segments [0];
					if (isset ( $segments [1] )) {
						$vars ['task'] = $segments [1];
					}
				}

				if (isset ( $mView ) && ($mView == 'plan' || $mView == 'Plan')) {
					$vars ['view'] = $mView;
					if (isset ( $segments [0] )) {
						$vars ['slug'] = $segments [0];
					}

				} elseif ($segments [0] == 'plan' || $segments [0] == 'Plan') {
					$vars ['view'] = $segments [0];
					if (isset ( $segments [1] )) {
						$vars ['slug'] = $segments [1];
					}
				}

				
				if (isset ( $mView ) && ($mView == 'subscribe' || $mView == 'Subscribe')) {
					$vars ['view'] = $mView;					
					if (isset ( $segments [0] )) {
						if (in_array($segments [0], $subs_tasks)) {
							$vars ['task'] = $segments [0];	
						}else {
							$vars ['plan'] = $segments [0];	
						}						
					}

				} elseif ($segments [0] == 'subscribe' || $segments [0] == 'Subscribe') {
					$vars ['view'] = $segments [0];
					if (isset ( $segments [1] )) {
						if (in_array($segments [1], $subs_tasks)) {
							$vars ['task'] = $segments [1];	
						}else {
							$vars ['plan'] = $segments [1];	
						}
					}
				}

				if (isset ( $mView ) && ($mView == 'profile' || $mView == 'Profile')) {
					$vars ['view'] = $mView;					
					if (isset ( $segments [0] )) {
						$vars ['task'] = $segments [0];
					}

				} elseif ($segments [0] == 'profile' || $segments [0] == 'Profile') {
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