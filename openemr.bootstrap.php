<?php
/**
 * Bootstrap custom Patient Privacy module.
 *
 * @package   OpenEMR
 * @link      http://www.open-emr.org
 * @author    Ken Chapple <ken@mi-squared.com>
 * @copyright Copyright (c) 2020 Ken Chapple <ken@mi-squared.com>
 * @license   https://github.com/openemr/openemr/blob/master/LICENSE GNU General Public License 3
 */
//namespace PatientPrivacy;
//require_once __DIR__.'/vendor/autoload.php';

use OpenEMR\Events\PatientFinder\PatientFinderFilterEvent;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use OpenEMR\Menu\MenuEvent;
use PatientPrivacy\PatientPrivacyService;

function oe_module_mi_messages_add_menu_item(MenuEvent $event)
{
    $menu = $event->getMenu();

    $menuItem = new stdClass();
    $menuItem->requirement = 0;
    $menuItem->target = 'adm';
    $menuItem->menu_id = 'adm0';
    $menuItem->label = xlt("MI-Messages");
    $menuItem->url = "/interface/modules/custom_modules/oe-mi-messages/index.php?action=admin";
    $menuItem->children = [];
    $menuItem->acl_req = ["admin", "super"];

    foreach ($menu as $item) {
        if ($item->menu_id == 'admimg') {
            array_unshift($item->children, $menuItem);
            break;
        }
    }

    $event->setMenu($menu);

    return $event;
}

// Listen for the menu update event so we can dynamically add our patient privacy menu item
$eventDispatcher->addListener(MenuEvent::MENU_UPDATE, 'oe_module_mi_messages_add_menu_item');
