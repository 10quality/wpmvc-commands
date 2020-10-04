<?php

namespace WPMVC\Commands\Traits;

use Exception;
use Ayuco\Exceptions\NoticeException;

/**
 * Trait used to set the phpunit and WordPress test suit.
 *
 * @author Ale Mostajo <http://about.me/amostajo>
 * @copyright 10 Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC\Commands
 * @version 1.1.12
 */
trait SetupTestsTrait
{
    /**
     * Setups unit testing.
     * @since 1.1.0
     */
    public function setupTests()
    {
        $configFilename = null;
        try {
            $this->_lineBreak();
            $this->_print('------------------------------');
            $this->_lineBreak();
            $this->_print('WordPress PHPUnit test suite setup wizard');
            $this->_lineBreak();
            // Request suit path
            $this->_print('------------------------------');
            $this->_lineBreak();
            $this->_print('Enter the path, in your computer, where you want "WordPress Test Suit" to be cloned:');
            $this->_lineBreak();
            $testSuitPath = $this->listener->getInput();
            if (!empty($testSuitPath)) {
                if (is_dir($testSuitPath)) {
                    $this->_print('The directory already exist, please provide a non existen path to clone the test suit.');
                    $this->_lineBreak();
                    $this->_print('Clonning will be skipped...');
                    $this->_lineBreak();
                } else {
                    // Clone
                    $this->_print('------------------------------');
                    $this->_lineBreak();
                    $this->_print('Clonning, please wait...');
                    $this->_lineBreak();
                    $this->_print(shell_exec(__DIR__.'/../../shell/install-test-suit.sh '.$testSuitPath.' 2>&1'));
                    $this->_print('Clonning, completed');
                    $this->_lineBreak();
                }
                // Creating wp-test-config.php
                $filename = $testSuitPath;
                if (substr( $testSuitPath, -1, 1 ) !== '/' && substr( $testSuitPath, -1, 1 ) !== '\\')
                    $filename .= '/';
                $filename .= 'wp-tests-config.php';
                $configFilename = ABSPATH.'/wp-tests-config.php';
                if (file_exists($filename)) {
                    $this->_print('------------------------------');
                    $this->_lineBreak();
                    if ($this->getYesInput('Would you like to create a new WP testing config file?')) {
                        $this->_print('------------------------------');
                        $this->_lineBreak();
                        // We need to create a file
                        $wpTestConfig = '';
                        while (!preg_match('/[\s\S]+\.php/', $wpTestConfig)) {
                            $this->_print('What would be the name of the file (must be .php)?');
                            $this->_lineBreak();
                            $this->_print('Press enter to default name to "wp-tests-config.php":');
                            $this->_lineBreak();
                            $wpTestConfig = $this->listener->getInput();
                            if ( empty( $wpTestConfig ) )
                                $wpTestConfig = 'wp-tests-config.php';
                        }
                        // Copy file
                        $config = file_get_contents( $filename );
                        $config = str_replace('define( \'ABSPATH\', \'/\')', 'define( \'ABSPATH\', dirname( __FILE__ ) . \'/\')', $config );
                        $this->_lineBreak();
                        if ($this->getYesInput('Would you like to configure the database connection?')) {
                            $this->_print('Enter the database host:');
                            $this->_lineBreak();
                            $dbOption = $this->listener->getInput();
                            $config = str_replace('\'DB_HOST\', \'\'', '\'DB_HOST\', \''.$dbOption.'\'', $config );
                            $this->_print('Enter the database name:');
                            $this->_lineBreak();
                            $dbOption = $this->listener->getInput();
                            $config = str_replace('\'DB_NAME\', \'\'', '\'DB_NAME\', \''.$dbOption.'\'', $config );
                            $this->_print('Enter the database user:');
                            $this->_lineBreak();
                            $dbOption = $this->listener->getInput();
                            $config = str_replace('\'DB_USER\', \'\'', '\'DB_USER\', \''.$dbOption.'\'', $config );
                            $this->_print('Enter the database password:');
                            $this->_lineBreak();
                            $dbOption = $this->listener->getInput();
                            $config = str_replace('\'DB_PASSWORD\', \'\'', '\'DB_PASSWORD\', \''.$dbOption.'\'', $config );
                            $config = str_replace('\'DB_CHARSET\', \'\'', '\'DB_CHARSET\', \'utf8\'', $config );
                        }
                        $configFilename = ABSPATH . '/' . $wpTestConfig;
                        file_put_contents( $configFilename, $config );
                        $this->_lineBreak();
                        $this->_print('WP testing config file created.');
                        $this->_lineBreak();
                    }
                }
            } else {
                $this->_print('Skipping suite installation.');
                $this->_lineBreak();
            }
            $this->_print('------------------------------');
            $this->_lineBreak();
            // phpunit.json
            if ($configFilename && file_exists($configFilename)) {
                file_put_contents($this->rootPath.'/phpunit.json', json_encode([
                    'wp_test_dir' => $testSuitPath,
                    'wp_tests_config_path' => $configFilename,
                ]));
                $this->_print('phpunit.json file created.');
                $this->_lineBreak();
            }
            // phpunit.xml
            if ( !file_exists($this->rootPath.'/phpunit.xml')) {
                $this->copyTemplate('phpunit.xml', $this->rootPath.'/phpunit.xml');
                $this->replaceInFile('\{0\}', $this->config['namespace'], $this->rootPath.'/phpunit.xml');
                $this->_print('phpunit.xml file created.');
                $this->_lineBreak();
            }
            // /tests
            if (!is_dir($this->rootPath.'/tests')) {
                $path = $this->rootPath.'/tests';
                $this->createPath($path);
                $this->copyTemplate('testbootstrap.php', $this->rootPath.'/tests/bootstrap.php');
                $this->replaceInFile('\{0\}', $this->config['type'] === 'theme' ? 'functions.php' : 'plugin.php', $this->rootPath.'/tests/bootstrap.php');
                $this->_print('test/bootstrap.php created.');
                $this->_lineBreak();
            }
            // /tests/cases
            if (!is_dir($this->rootPath.'/tests/cases')) {
                $path = $this->rootPath.'/tests/cases';
                $this->createPath($path);
                $this->_print('test/cases path created.');
                $this->_lineBreak();
            }
            // PHPUnit
            exec('composer require phpunit/phpunit:7.5.* --dev --no-plugins');
            // Complete
            $this->_print('------------------------------');
            $this->_lineBreak();
            $this->_print('WordPress PHPUnit test suite setup completed');
            $this->_lineBreak();
            $this->_print('------------------------------');
            $this->_lineBreak();

        } catch (Exception $e) {
            file_put_contents(
                $this->rootPath.'/error_log',
                $e->getMessage()
            );
            throw new NoticeException('Command "'.$this->key.'": Fatal error occurred.');
        }
    }
}