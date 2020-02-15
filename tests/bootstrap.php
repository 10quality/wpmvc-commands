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
if (!is_dir(TESTING_PATH.'/app/'))
  mkdir(TESTING_PATH.'/app/');
$candidate = TESTING_PATH.'/app/Main.php';
if (file_exists($candidate))
    unlink($candidate);
file_put_contents($candidate, '<?php namespace MyApp; use WPMVC\Bridge;
/**
 * @author fill
 * @package fill
 * @version fill
 */
class Main extends Bridge { public function init(){ } public function on_admin() { } }');
// composer.json
$candidate = TESTING_PATH.'/composer.json';
if (file_exists($candidate))
    unlink($candidate);
file_put_contents($candidate, '{
  "name": "wpmvc/my-app",
  "autoload":{
    "psr-4": {
      "MyApp\\\\": "app"
    }
  }
}');
// package.json
$candidate = TESTING_PATH.'/package.json';
if (file_exists($candidate))
    unlink($candidate);
file_put_contents($candidate, '{
  "name": "my-app",
  "version": "1.0.0",
  "dependencies": {
    "gulp": "^3.9.1",
    "gulp-wpmvc": "^1.0.*"
  }
}');
// Config/app.php
if (!is_dir(TESTING_PATH.'/app/Config/'))
  mkdir(TESTING_PATH.'/app/Config/');
$candidate = TESTING_PATH.'/app/Config/app.php';
if (file_exists($candidate))
    unlink($candidate);
file_put_contents($candidate, '<?php
return [
    \'namespace\' => \'MyApp\',
    \'type\' => \'theme\',
    \'version\' => \'1.0.0\',
    \'author\' => \'Developer <developer@wpmvc>\',
    \'paths\' => [
        \'base\'          => __DIR__ . \'/../\',
        \'controllers\'   => __DIR__ . \'/../Controllers/\',
        \'views\'         => __DIR__ . \'/../../assets/views/\',
    ],
    \'localize\' => [
        \'textdomain\'    => \'my-app\',
        \'path\'          => __DIR__ . \'/../../assets/lang/\',
    ],
];');
// style.css
$candidate = TESTING_PATH.'/style.css';
if (file_exists($candidate))
    unlink($candidate);
file_put_contents($candidate, '/*
Theme Name: [MY THEME]
Theme URI: [MY URL]
Author: [MY NAME OR COMPANY NAME]
Author URI: [MY URL]
Description: [THEME DESCRIPTION]
Version: 1.0.0
License: [LICENSE]
License URI: [LICENSE URL]
Tags: [TAGS SEPARATED BY COMMAS i.e. bootstrap, red, responsive]
Text Domain: my-app
Add additional description here...
*/');