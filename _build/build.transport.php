<?php
$mtime = microtime();
$mtime = explode(" ", $mtime);
$mtime = $mtime[1] + $mtime[0];
$tstart = $mtime;
// get rid of time limit
set_time_limit(0);
$root = dirname(dirname(__FILE__)) . '/';
$sources= array (
    'root' => $root,
    'assets' => $root . 'assets/',
    'build' => $root . '_build/',
    'lexicon' => $root . '_build/lexicon/',
    'data' => $root . '_build/data/',
    'resolvers' => $root . '_build/resolvers/',
);

// override with your own defines here (see build.config.sample.php)
require_once $sources['build'].'build.config.php';
require_once MODX_CORE_PATH . 'model/modx/modx.class.php';

$modx= new modX();
$modx->initialize('mgr');
echo '<pre>'; // used for nice formatting of log messages
$modx->setLogLevel(MODX_LOG_LEVEL_INFO);
$modx->setLogTarget('ECHO');

$name = 'captcha';
$version = '3.0.1';
$release = 'beta';

$modx->loadClass('transport.modPackageBuilder','',false, true);
$builder = new modPackageBuilder($modx);
$builder->create($name, $version, $release);
$builder->registerNamespace($name,false,true);


// get the source from the actual snippet in your database OR
// manually create the object, grabbing the source from a file
$c= $modx->newObject('modPlugin');
$c->set('name', 'Captcha');
$c->set('description', '<strong>3.0.1-beta</strong> CAPTCHA Login Plugin');
$c->set('category', 0);
$c->set('plugincode', file_get_contents($sources['assets'] . 'captcha/plugin.captcha.php'));

// create a transport vehicle for the data object
$attributes= array(
    XPDO_TRANSPORT_UNIQUE_KEY => 'name',
    XPDO_TRANSPORT_UPDATE_OBJECT => true,
);
$vehicle = $builder->createVehicle($c, $attributes);
$vehicle->resolve('file',array(
    'source' => $sources['assets'] . 'captcha',
    'target' => "return MODX_ASSETS_PATH . '/';",
));
$vehicle->resolve('php',array(
    'source' => $sources['resolvers'] . 'plugin_events.php',
));
$builder->putVehicle($vehicle);

// load lexicon strings
$builder->buildLexicon($sources['lexicon']);

// add system settings
$settings = array();
include_once $sources['data'] . 'transport.settings.php';
$attributes= array(
    XPDO_TRANSPORT_UNIQUE_KEY => 'key',
    XPDO_TRANSPORT_PRESERVE_KEYS => true,
    XPDO_TRANSPORT_UPDATE_OBJECT => true,
);
foreach ($settings as $key => $setting) {
    $vehicle = $builder->createVehicle($setting,$attributes);
    $builder->putVehicle($vehicle);
}

// zip up the package
$builder->pack();

$mtime= microtime();
$mtime= explode(" ", $mtime);
$mtime= $mtime[1] + $mtime[0];
$tend= $mtime;
$totalTime= ($tend - $tstart);
$totalTime= sprintf("%2.4f s", $totalTime);

$modx->log(MODX_LOG_LEVEL_INFO,"\n<br />Package Built.<br />\nExecution time: {$totalTime}\n");

exit ();
