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
    'resolvers' => $root . '_build/resolvers/',
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
$modx->setLogLevel(MODX_LOG_LEVEL_INFO);
$modx->setLogTarget('ECHO');

$name = 'captcha';
$version = '3.0.3';
$release = 'beta1';

$modx->loadClass('transport.modPackageBuilder','',false, true);
$builder = new modPackageBuilder($modx);
$builder->createPackage($name, $version, $release);
$builder->registerNamespace($name,false,true);


/* get the source from the actual snippet in your database OR
/* manually create the object, grabbing the source from a file
 */
$c= $modx->newObject('modPlugin');
$c->set('id',1);
$c->set('name', 'Captcha');
$c->set('description', '<b>3.0.3-beta1</b> CAPTCHA Login Plugin');
$c->set('category', 0);
$c->set('plugincode', file_get_contents($sources['source_core'] . '/plugin.captcha.php'));

/* create a transport vehicle for the data object */
$attributes= array(
    XPDO_TRANSPORT_UNIQUE_KEY => 'name',
    XPDO_TRANSPORT_PRESERVE_KEYS => false,
    XPDO_TRANSPORT_UPDATE_OBJECT => true,
);
$vehicle = $builder->createVehicle($c, $attributes);
$vehicle->resolve('file',array(
    'source' => $sources['source_assets'],
    'target' => "return MODX_ASSETS_PATH . '/components/';",
));
$vehicle->resolve('file',array(
    'source' => $sources['source_core'],
    'target' => "return MODX_CORE_PATH . '/components/';",
));
$vehicle->resolve('php',array(
    'source' => $sources['resolvers'] . 'plugin_events.php',
));
$builder->putVehicle($vehicle);

/* load lexicon strings */
$builder->buildLexicon($sources['lexicon']);

/* add system settings */
$settings = array();
include_once $sources['data'] . 'transport.settings.php';
$attributes= array(
    XPDO_TRANSPORT_UNIQUE_KEY => 'key',
    XPDO_TRANSPORT_PRESERVE_KEYS => true,
    XPDO_TRANSPORT_UPDATE_OBJECT => false, /* dont update the setting, leave to user-defined values */
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

$modx->log(MODX_LOG_LEVEL_INFO,"\n<br />Package Built.<br />\nExecution time: {$totalTime}\n");

exit ();