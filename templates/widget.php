<?php

/**
 * {0} Widget.
 * Generated with ayuco.
 *
 * @author fill
 * @version fill
 */
class {0} extends WP_Widget
{
    /**
     * MAIN reference
     * @var object
     * @since fill
     */
    protected $main;

    /**
     * Constructor.
     * @since fill
     *
     * @global Main ${1} theme's main class.
     */
    public function __construct( $id = '', $name = '', $args = array() )
    {
        global ${1};
        $this->main = ${1};
        parent::__construct(
            'widget-id', // Widget ID
            'Widget name', // Widget name
            [
                'classname'     => '{0}', // Widget class name
                'description'   => __( 'Widget description.', '{2}' ), // Widget description
            ]
        );
    }

    /**
     * Widget display.
     * Renders what will be inside the widget when displayed.
     * @since fill
     *
     * @param array $args     Arguments for the theme.
     * @param class $instance Parameters.
     */
    public function widget( $args, $instance )
    {
        // ----------------
        // TODO code.
        // ----------------
        echo $args['before_widget'];

        // ----------------
        // TODO display widget.
        // Example:
        //
        // $this->main->view( 'widgets.widget-id' );
        // ----------------

        echo $args['after_widget'];
    }

    /**
     * Widget update.
     * Called when user updates settings at widget setting in admin dashboard.
     * @since fill
     *
     * @param array $new_instance Widget instance.
     * @param array $old_instance Widget instance.
     *
     * @return array
     */ 
    public function update( $new_instance, $old_instance )
    {
        $instance = $old_instance;

        // ----------------
        // TODO data save.
        // Example:
        //
        // $instance['limit'] = intval( $new_instance['limit'] );
        // ----------------

        return $instance;
    }

    /**
     * Widget form.
     * Renders the form displayed  at widget setting in admin dashboard.
     * @since fill
     *
     * @param array $new_instance Widget instance.
     * @param array $old_instance Widget instance.
     */
    public function form( $instance )
    {
        // ----------------
        // TODO set defaults.
        //
        // $instance = wp_parse_args( (array)$instance, [
        //     'limit'     => 3,
        // ] );
        // ----------------

        // ----------------
        // TODO display form
        //
        // $this->main->view( 'admin.widgets.widget-id', [
        //     'widget'    => $this,
        //     'instance'  => $instance,
        // ] );
        // ----------------
    }
}