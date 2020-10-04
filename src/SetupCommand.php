<?php

namespace WPMVC\Commands;

use WPMVC\Commands\Base\BaseCommand as Command;
use Ayuco\Exceptions\NoticeException;
use WPMVC\Commands\Traits\UpdateCommentTrait;

/**
 * Setup command.
 *
 * @author Alejandro Mostajo <http://about.me/amostajo>
 * @copyright 10Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC\Commands
 * @version 1.1.12
 */
class SetupCommand extends Command
{
    use UpdateCommentTrait;
    /**
     * Command key.
     * @since 1.0.0
     * @var string
     */
    protected $key = 'setup';

    /**
     * Command description.
     * @since 1.0.0
     * @var string
     */
    protected $description = 'WordPress MVC setup wizard.';

    /**
     * Calls to command action.
     * @since 1.0.0
     *
     * @param array $args Action arguments.
     */
    public function call($args = [])
    {
        // Check for MVC configuration file
        if (empty($this->configFilename))
            throw new NoticeException('Command "'.$this->key.'": No configuration file found.');
        
        $command = $this->listener->get('set');
        if (!$command)
            throw new NoticeException('SetupCommand: "set" command is not registered in ayuco.');

        try {
            $this->_print('------------------------------');
            $this->_lineBreak();
            $this->_print('WordPress MVC (AYUCO) Setup');
            $this->_lineBreak();
            // TYPE
            $this->_print('------------------------------');
            $this->_lineBreak();
            $this->_print('Enter your project\'s type (options: "theme" or "plugin"):');
            $this->_lineBreak();
            $type = $this->listener->getInput();
            $this->setType($type);
            // PROJECT NAME
            $this->_print('------------------------------');
            $this->_lineBreak();
            $this->_print('Enter your project\'s name (example: My App):');
            $this->_lineBreak();
            $name = $this->listener->getInput();
            if (empty($name))
                $name = 'My App';
            // NAMESPACE
            $this->_print('------------------------------');
            $this->_lineBreak();
            $this->_print('Enter your project\'s namespace (PHP unique namespace, example: MyApp):');
            $this->_lineBreak();
            $namespace = $this->listener->getInput();
            $namespace = empty($namespace) ? 'MyApp' : str_replace(' ', '', ucwords($namespace));
            $command->setNamespace($namespace);
            // DESCRIPTION
            $this->_print('------------------------------');
            $this->_lineBreak();
            $this->_print('Enter your project\'s description:');
            $this->_lineBreak();
            $description = $this->listener->getInput();
            // DOMAIN PATH
            $this->_print('------------------------------');
            $this->_lineBreak();
            $this->_print('Enter your project\'s text domain (example: my-app), as WordPress standard, it should be the same as the project\'s root folder, this will be used for localization and builds:');
            $this->_lineBreak();
            $domain = $this->listener->getInput();
            $domain = empty($domain) ? 'my-app' : $domain;
            $command->setTextDomain(empty($domain) ? 'my-app' : $domain);
            // AUTHOR
            $command->setAuthor();
            // LICENSE
            $command->setLicense($license);
            $this->config = include $this->configFilename;
            // Update main
            $this->updateComment('author', $this->config['author'], $this->getMainClassPath());
            $this->updateComment('version', $this->config['version'], $this->getMainClassPath());
            $this->updateComment('package', $domain, $this->getMainClassPath());
            $this->updateComment('license', $this->config['license'], $this->getMainClassPath());
            // PLUGIN URL
            $this->_print('------------------------------');
            $this->_lineBreak();
            $this->_print('Enter your project\'s URL (i.e. the repository URL or a web page):');
            $this->_lineBreak();
            $url = $this->listener->getInput();
            $tags = '';
            if ($this->config['type'] === 'theme') {
                $this->_print('Enter your theme\'s tags (separated by commas, i.e. "tag1, tag2"):');
                $this->_lineBreak();
                $tags = $this->listener->getInput();
            }
            // Update plugin/theme file
            $this->setInfo([
                '\[MY APP\]' => $name,
                '\[MY DESCRIPTION\]' => $description,
                '\[MY NAME OR COMPANY\]' => $this->getAuthorName(),
                '\[MY COMPANY URL\]' =>$this->getAuthorUrl(),
                '\[MY URL\]' => $url,
                '\[LICENSE\]' => $license['name'],
                '\[LICENSE URL\]' => $license['url'],
                '\[TAGS\]' => $tags,
            ]);
            // DESCRIPTION
            $this->_print('------------------------------');
            $this->_lineBreak();
            $this->_print($this->description);
            $this->_lineBreak();
            // End
            $this->_print('------------------------------');
            $this->_lineBreak();
            $this->_print('Your project\'s name is "%s"', $name);
            $this->_lineBreak();
            $this->_print('Your project\'s namespace is "%s"', $namespace);
            $this->_lineBreak();
            $this->_print('Your project\'s text domain is "%s"', $domain);
            $this->_lineBreak();
            $this->_print('Your project\'s author is "%s"', $this->config['author']);
            $this->_lineBreak();
            $this->_print('Your project\'s license is "%s"', $this->config['license']);
            $this->_lineBreak();
            $this->_print('Setup completed!');
            $this->_lineBreak();
            $this->_print('------------------------------');
            $this->_lineBreak();
        } catch (NoticeException $e) {
            throw new NoticeException('Command "setup": Failed! ' . $e->getMessage());
        }
    }

    /**
     * Sets project type.
     * @since 1.0.0
     *
     * @param string $type Type.
     */
    private function setType($type = '')
    {
        $type = preg_replace('/\"/', '', mb_strtolower($type));
        if ($type !== 'p' && $type !== 'plugin' && $type !== 't' && $type !== 'theme')
            throw new NoticeException('Type is wrong. Must be either "theme" or "plugin".');

        if ($type === 'p')
            $type = 'plugin';
        if ($type === 't')
            $type = 'theme';

        // Type handling
        $currentType = $this->config['type'];
        $this->replaceInFile('\''.$currentType.'\'', '\''.$type.'\'', $this->configFilename);
        $this->config = include $this->configFilename;

        $this->_print('Type set!');
        $this->_lineBreak();

        // Basic file structure
        switch ($type) {
            case 'plugin':
                $this->copyTemplate('plugin.php', $this->rootPath.'/plugin.php');
                break;
            case 'theme':
                $this->copyTemplate('functions.php', $this->rootPath.'/functions.php');
                $this->copyTemplate('index.php', $this->rootPath.'/index.php');
                $this->copyTemplate('style.css', $this->rootPath.'/style.css');
                break;
        }

        $this->_print('Project\'s base file(s) generated!');
        $this->_lineBreak();
    }
    /**
     * Sets APP information.
     * @since 1.1.12
     *
     * @param array $info
     */
    private function setInfo($info)
    {
        $filename = $this->config['type'] === 'plugin' ? $this->rootPath.'/plugin.php' : $this->rootPath.'/style.css';
        foreach ($info as $key => $value) {
            $this->replaceInFile($key, $value, $filename);
        }
    }
    /**
     * Returns author name.
     * @since 1.1.12
     *
     * @return string
     */
    private function getAuthorName()
    {
        $author = explode('<', $this->config['author']);
        return trim($author[0]);
    }
    /**
     * Returns author URL.
     * @since 1.1.12
     *
     * @return string|null
     */
    private function getAuthorUrl()
    {
        $author = explode('<', $this->config['author']);
        if (count($author) > 1) {
            $url = trim(str_replace('>', '', $author[1]));
            return strpos($url, '@') !== false ? 'mailto:'.$url : $url;
        }
        return;
    }
}
