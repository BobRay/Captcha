<?php
/**
 * Resolver for handling addition of plugin events
 *
 * TODO: move to related objects in build
 * @package captcha
 */
$success= false;
if ($pluginid= $object->get('id')) {
    switch ($options[XPDO_TRANSPORT_PACKAGE_ACTION]) {
        case XPDO_TRANSPORT_ACTION_INSTALL:
        case XPDO_TRANSPORT_ACTION_UPGRADE:
            $evt = $object->xpdo->newObject('modPluginEvent');
            $evt->set('pluginid',$pluginid);
            $evt->set('evtid',80);
            $success= $evt->save();
            unset($evt);

            $evt = $object->xpdo->newObject('modPluginEvent');
            $evt->set('pluginid',$pluginid);
            $evt->set('evtid',93);
            $success= $evt->save();
            unset($evt);
            break;

        case XPDO_TRANSPORT_ACTION_UNINSTALL:
            $success= true;
            break;
    }
}

return $success;