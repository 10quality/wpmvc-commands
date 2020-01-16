<?php
// TESTING BOOTSTRAP
require_once __DIR__.'/../vendor/autoload.php';
// TESTING FRAMEWORK
require_once __DIR__.'/../vendor/10quality/ayuco/tests/framework/class.AyucoTestCase.php';
require_once __DIR__.'/../vendor/10quality/ayuco/tests/framework/class.AyucoBuilder.php';
require_once __DIR__.'/framework/class.WpmvcAyucoTestCase.php';
// Constant
define('FRAMEWORK_PATH', __DIR__);
define('ENV_PATH', __DIR__.'/environments');
define('WPMVC_AYUCO', FRAMEWORK_PATH.'/environment/ayuco');
define('TESTING_PATH', __DIR__.'/environment');
// Delete existing main
$main = TESTING_PATH.'/app/Main.php';
if (file_exists($main))
    unlink($main);
file_put_contents($main, '<?php namespace MyApp; use WPMVC\Bridge; class Main extends Bridge { public function init(){ } public function on_admin() { } }');