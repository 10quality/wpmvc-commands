const { registerBlockType } = wp.blocks;
/*!
 * {0} WordPress block.
 * JSX syntax.
 *
 * @author {3}
 * @package {2}
 * @version {1}
 */
registerBlockType( '{2}/{0}', {
    /**
     * Title (this is the title name that will appear inside WordPress editor).
     * @since {1}
     *
     * @var {string}
     */
    title: '',
    /**
     * Description.
     * @since {1}
     *
     * @var {string}
     */
    description: '',
    /**
     * Icon (dashicon or SVG).
     * @since {1}
     *
     * @var {string}
     */
    icon: '',
    /**
     * Category.
     * @since {1}
     *
     * @var {string}
     */
    category: 'common',
    /**
     * Custom block attributes.
     * @since {1}
     *
     * @var {object}
     */
    attributes: {},
    /**
     * @since {1}
     */
    edit() {
        return <div></div>;
    },
    /**
     * @since {1}
     */
    save() {},
} );