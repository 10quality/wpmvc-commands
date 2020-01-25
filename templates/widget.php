<?php

/**
 * {5}
 * WordPress Widget.
 *
 * @author {2}
 * @package {3}
 * @version {4}
 */
class {0} extends WP_Widget
{
    /**
     * MAIN reference
     * @var object
     * @since {4}
     */
    protected $main;
    /**
     * Constructor.
     * @since {4}
     */
    public function __construct( $id = '', $name = '', $args = array() )
    {
        $this->main = get_bridge( '{1}' );
        parent::__construct(
            'widget-id', // Widget ID
            __( 'Widget name', '{3}' ), // Widget name
            [
                'classname'     => '{0}', // Widget class name
                'description'   => __( 'Widget description.', '{3}' ), // Widget description
            ]
        );
    }
    /**
     * Widget display.
     * Renders what will be inside the widget when displayed.
     * @since {4}
     *
     * @param array $args     Arguments.
     * @param class $instance Instance parameters.
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
     * @since {4}
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
     * @since {4}
     *
     * @param array $instance Saved widget instance.
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