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