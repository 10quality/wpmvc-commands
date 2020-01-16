<?php
/**
 * Post type {0} meta fields form.
 * Automated metabox.
 * Generated with ayuco.
 *
 * @author {1}
 * @package {2}
 * @version {3}
 */
?>
<table class="form-table">
    <?php foreach ( $model->aliases as $alias => $meta_field ) : ?>
        
        <?php if ( preg_match( '/meta\_/', $meta_field ) ) : ?>

            <tr valign="top">
                <th scope="row"><?php echo ucfirst( preg_replace( '/\-|_|\./', ' ', $alias ) ) ?></th>
                <td>
                    <input type="text"
                        name="<?php echo $meta_field ?>"
                        value="<?php echo $model->$alias ?>"
                    />
                </td>
            </tr>

        <?php endif ?>

    <?php endforeach ?>
</table>