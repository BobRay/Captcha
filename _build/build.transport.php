<?php
/**
 * Captcha build script
 *
 * @author bobray <bobray@softville.com>
 * @package captcha
 * @subpackage build
 */
$mtime = microtime();
$mtime = explode(" ", $mtime);
$mtime = $mtime[1] + $mtime[0];
$tstart = $mtime;
/* get rid of time limit */
set_time_limit(0);
$root = dirname(dirname(__FILE__)) . '/';
$sources= array (
    'root' => $root,
    'build' => $root . '_build/',
    'data' => $root . '_build/data/',
    'lexicon' => $root . 'core/components/captcha/lexicon/',
    'docs' => $root . 'core/components/captcha/docs/',
    'source_assets' => $root . 'assets/components/captcha',
    'source_core' => $root . 'core/components/captcha',
);

/* override with your own defines here (see build.config.sample.php) */
require_once $sources['build'].'build.config.php';
require_once MODX_CORE_PATH . 'model/modx/modx.class.php';

$modx= new modX();
$modx->initialize('mgr');
echo '<pre>'; /* used for nice formatting of log messages */
$modx->setLogLevel(modX::LOG_LEVEL_INFO);
$modx->setLogTarget('ECHO');

$name = 'captcha';
$version = '3.2.1';
$release = 'beta1';

$modx->loadClass('transport.modPackageBuilder','',false, true);
$builder = new modPackageBuilder($modx);
$builder->createPackage($name, $version, $release);
$builder->registerNamespace($name,false,true,'{core_path}components/' . $name. '/');


/* get the source from the actual snippet in your database OR
/* manually create the object, grabbing the source from a file
 */
$c= $modx->newObject('modPlugin');
$c->set('id',1);
$c->set('name', 'Captcha');
$c->set('description', '<b>3.2.1-beta1</b> CAPTCHA Login Plugin');
$c->set('category', 0);
$c->set('plugincode', file_get_contents($sources['source_core'] . '/plugin.captcha.php'));

/* create a transport vehicle for the data object */

/* add plugin events */
$modx->log(xPDO::LOG_LEVEL_INFO,'Packaging in Plugin Events...'); flush();
$events = include $sources['data'].'plugin_events.php';
if (is_array($events) && !empty($events)) {
$c->addMany($events);
} else {
$modx->log(xPDO::LOG_LEVEL_ERROR,'Could not find plugin events!');
}
$modx->log(xPDO::LOG_LEVEL_INFO,'Plugin Events Packaged...'); flush();

$modx->log(xPDO::LOG_LEVEL_INFO,'Setting Package Attributes...'); flush();
$attributes= array(
    xPDOTransport::UNIQUE_KEY => 'name',
    xPDOTransport::PRESERVE_KEYS => false,
    xPDOTransport::UPDATE_OBJECT => true,
    xPDOTransport::RELATED_OBJECTS => true,
    xPDOTransport::RELATED_OBJECT_ATTRIBUTES => array (
       'PluginEvents' => array(
           xPDOTransport::PRESERVE_KEYS => true,
           xPDOTransport::UPDATE_OBJECT => false,
           xPDOTransport::UNIQUE_KEY => array('pluginid','event'),
       ),
    ),
);
$modx->log(xPDO::LOG_LEVEL_INFO,'Package Attributes Set...'); flush();
$modx->log(xPDO::LOG_LEVEL_INFO,'Creating Vehicle...'); flush();
$vehicle = $builder->createVehicle($c, $attributes);

$vehicle->validate('php',array(
    'type' => 'php',
    'source' => $sources['build'] . 'preinstall-script.php'
));

$vehicle->resolve('file',array(
    'source' => $sources['source_assets'],
    'target' => "return MODX_ASSETS_PATH . '/components/';",
));
$vehicle->resolve('file',array(
    'source' => $sources['source_core'],
    'target' => "return MODX_CORE_PATH . '/components/';",
));

$builder->putVehicle($vehicle);
$modx->log(xPDO::LOG_LEVEL_INFO,'Vehicle Packaged...'); flush();

/* add system settings */
$settings = array();
include_once $sources['data'] . 'transport.settings.php';
$attributes= array(
    xPDOTransport::UNIQUE_KEY => 'key',
    xPDOTransport::PRESERVE_KEYS => true,
    xPDOTransport::UPDATE_OBJECT => false, /* dont update the setting, leave to user-defined values */
);
foreach ($settings as $key => $setting) {
    $vehicle = $builder->createVehicle($setting,$attributes);
    $builder->putVehicle($vehicle);
}

/* now pack in the license file, readme and setup options */
$builder->setPackageAttributes(array(
    'license' => file_get_contents($sources['docs'] . 'license.txt'),
    'readme' => file_get_contents($sources['docs'] . 'readme.txt'),
    'setup-options' => '',
));

/* zip up the package */
$builder->pack();

$mtime= microtime();
$mtime= explode(" ", $mtime);
$mtime= $mtime[1] + $mtime[0];
$tend= $mtime;
$totalTime= ($tend - $tstart);
$totalTime= sprintf("%2.4f s", $totalTime);

$modx->log(modX::LOG_LEVEL_INFO,"\n<br />Package Built.<br />\nExecution time: {$totalTime}\n");

exit ();