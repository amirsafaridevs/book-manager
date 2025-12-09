<?php


// Add nonce for security
wp_nonce_field('book_isbn_meta_box', 'book_isbn_meta_box_nonce');



// Display the form
?>
<table class="form-table">
    <tr>
        <th scope="row">
            <label for="book_isbn"><?php _e('ISBN Number', 'book-manager'); ?></label>
        </th>
        <td>
            <input 
                type="text" 
                id="book_isbn" 
                name="book_isbn" 
                value="<?php echo esc_attr($isbn); ?>" 
                class="regular-text"
                placeholder="<?php esc_attr_e('Enter ISBN number', 'book-manager'); ?>"
            />
            <p class="description">
                <?php _e('Enter the International Standard Book Number (ISBN) for this book.', 'book-manager'); ?>
            </p>
        </td>
    </tr>
</table>
