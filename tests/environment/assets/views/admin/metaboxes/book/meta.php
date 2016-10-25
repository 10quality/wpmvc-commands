<?php
/**
 * Post type admin.metaboxes.book.meta meta fields form.
 * Automated metabox.
 * Generated with ayuco.
 *
 * @author fill
 * @version fill
 */
?>
<table class="form-table">
    <?php foreach ( $model->aliases as $alias => $meta_field ) : ?>
        
        <?php if ( preg_match( '/meta\_/', $meta_field ) ) : ?>

            <tr valign="top">
                <th scope="row"><?= ucfirst( preg_replace( '/\-\_/', ' ', $alias ) ) ?></th>
                <td>
                    <input type="text"
                        name="<?= $meta_field ?>"
                        value="<?= $model->$alias ?>"
                    />
                </td>
            </tr>

        <?php endif ?>

    <?php endforeach ?>
</table>