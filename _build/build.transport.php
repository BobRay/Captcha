<?php
$mtime = microtime();
$mtime = explode(" ", $mtime);
$mtime = $mtime[1] + $mtime[0];
$tstart = $mtime;

// get rid of time limit
set_time_limit(0);

// override with your own defines here (see build.config.sample.php)
require_once dirname(__FILE__).'/build.config.php';

require_once (MODX_CORE_PATH . 'model/modx/modx.class.php');
$modx= new modX();
$modx->initialize('mgr');
$modx->setDebug(true);

$name = 'captcha';
$version = '1.0';
$release = 'beta';

$modx->loadClass('transport.modPackageBuilder','',false, true);
$builder = new modPackageBuilder($modx);
$builder->create($name, $version, $release);
$builder->registerNamespace($name,false,true);

$sources= array (
    'root' => dirname(dirname(__FILE__)) . '/',
    'assets' => dirname(dirname(__FILE__)) . '/assets/',
);

// get the source from the actual snippet in your database OR
// manually create the object, grabbing the source from a file
$c= $modx->newObject('modPlugin');
$c->set('name', 'captcha');
$c->set('description', '<strong>1.0-beta</strong> CAPTCHA Login Plugin');
$c->set('category', 0);
$c->set('snippet', file_get_contents($sources['assets'] . 'captcha/plugin.captcha.php'));

// create a transport vehicle for the data object
$attributes= array(
    XPDO_TRANSPORT_UNIQUE_KEY => 'name',
    XPDO_TRANSPORT_UPDATE_OBJECT => true,
);
$vehicle = $builder->createVehicle($c, $attributes);
$vehicle->resolve('file',array(
    'source' => $sources['assets'] . 'captcha',
    'target' => "return MODX_ASSETS_PATH . 'captcha/';",
));
$builder->putVehicle($vehicle);

// load lexicon strings
$builder->buildLexicon($sources['root'].'_build/lexicon/');

// add system settings
$settings = array();
include_once dirname(__FILE__).'/data/transport.settings.php';
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

echo "\nExecution time: {$totalTime}\n";

exit ();
