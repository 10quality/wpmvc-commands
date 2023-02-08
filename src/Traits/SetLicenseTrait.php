<?php

namespace WPMVC\Commands\Traits;

use Exception;
use Ayuco\Exceptions\NoticeException;

/**
 * Trait used to set the project's license.
 *
 * @author Ale Mostajo <http://about.me/amostajo>
 * @copyright 10Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC\Commands
 * @version 1.1.17
 */
trait SetLicenseTrait
{
    /**
     * Sets a projects license.
     * @since 1.1.12
     *
     * @param array &$license
     */
    public function setLicense(&$input = null)
    {
        try {
            $input = ['name' => null, 'url' => null, 'known' => false];
            $known = [
                'Apache-2.0' => 'https://opensource.org/licenses/Apache-2.0',
                'MIT' => 'https://opensource.org/licenses/MIT',
                'GNU GPLv3' => 'https://www.gnu.org/licenses/gpl-3.0.en.html',
                'MPL-2.0' => 'https://opensource.org/licenses/MPL-2.0',
                'EPL-2.0' => 'https://opensource.org/licenses/EPL-2.0',
                'Unlicense' => 'https://choosealicense.com/licenses/unlicense/',
            ];
            // Ask for license information
            $this->_print('------------------------------');
            $this->_lineBreak();
            $this->_print('Enter the license\'s code/name (example: MIT, GNU GPLv3, Apache or other):');
            $this->_lineBreak();
            $input['name'] = $this->listener->getInput();
            $found = array_filter($known, function($key) use(&$input) {
                $key = strtolower($key);
                $name = strtolower($input['name']);
                return $key === $name
                    || ($key === 'apache-2.0' && $name === 'apache')
                    || ($key === 'gnu gplv3' && strpos($name, 'gnu') !== false)
                    || ($key === 'gnu gplv3' && strpos($name, 'gpl') !== false)
                    || ($key === 'mpl-2.0' && $name === 'mozilla')
                    || ($key === 'mpl-2.0' && $name === 'moz')
                    || ($key === 'epl-2.0' && $name === 'eclipse');
            }, ARRAY_FILTER_USE_KEY);
            if (count($found)) {
                foreach ($found as $key => $url) {
                    $input['name'] = $key;
                    $input['url'] = $url;
                    $input['known'] = true;
                    break;
                }
            } else {
                $this->_print('Enter the license\'s URL:');
                $this->_lineBreak();
                $input['url'] = $this->listener->getInput();
            }
            // Prepare author
            $license = empty($input['name']) ? null : $input['name'];
            if ($license && empty($input['known']) && !empty($input['url']))
                $license .= ' <'.$input['url'].'>';
            if (array_key_exists('author', $this->config)) {
                $current = $this->config['license'];
                // Sanitize URLs for Regex replacement
                if (strpos($current, '/') !== false)
                    $current = str_replace('/','\/',$current);
                // Replace in config file
                $this->replaceInFile('license\'(|\s)=>(|\s)\''.$current.'\'', 'license\' => \''.$license.'\'', $this->configFilename);
                $this->config = include $this->configFilename;
                // Print end
                $this->_print_success('License updated!');
                $this->_lineBreak();
            } else {
                throw new NoticeException('License key missing at "app/Config/app.php". Add the "license" setting in the configuration file to use this command.');
            }
        } catch (Exception $e) {
            file_put_contents(
                $this->rootPath.'/error_log',
                $e->getMessage()
            );
            throw new NoticeException('Command "'.$this->key.'": Fatal error occurred.');
        }
    }
}