<?php

namespace MyApp;

use WPMVC\Bridge;
/**
 * Main class.
 * Bridge between Wordpress and App.
 * Class contains declaration of hooks and filters.
 *
 * @author fill
 * @version fill
 */
class Main extends Bridge
{
    /**
     * Declaration of public wordpress hooks.
     * @since fill version
     */
    public function init()
    {
        // Ayuco: addition 2017-04-21 11:31 am
        $this->add_widget('Test');
        // Ayuco: addition 2017-04-21 11:36 am
        $this->add_widget('Test');
        // Ayuco: addition 2017-04-21 11:37 am
        $this->add_widget('Test');
        // Ayuco: addition 2017-04-21 11:39 am
        $this->add_widget('Test');
        // Ayuco: addition 2017-04-21 11:40 am
        $this->add_widget('Test');
        // Ayuco: addition 2017-04-21 11:42 am
        $this->add_widget('Test');
        // Ayuco: addition 2017-04-21 11:42 am
        $this->add_widget('Test');
        // Ayuco: addition 2017-04-21 11:42 am
        $this->add_widget('Test');
    }
    /**
     * Declaration of admin only wordpress hooks.
     * For Wordpress admin dashboard.
     * @since fill version
     */
    public function on_admin()
    {
    }
}