<?php
$success= false;
if ($pluginid= $object->get('id')) {
    switch ($options[XPDO_TRANSPORT_PACKAGE_ACTION]) {
        case XPDO_TRANSPORT_ACTION_INSTALL:
        case XPDO_TRANSPORT_ACTION_UPDATE:
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