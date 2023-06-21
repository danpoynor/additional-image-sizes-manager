<?php
// Add additional menu item to the Settings menu
function aism_add_menu_item() {
    add_options_page(
        'Additional Image Sizes Manager', 
        'Additional Image Sizes Manager', 
        'manage_options', 
        'additional_image_sizes_manager', 
        'aism_settings_page'
    );
}
add_action('admin_menu', 'aism_add_menu_item');

// Register additional image sizes settings page
function aism_settings_page() {
    if (!current_user_can('manage_options')) {
        return;
    }

    $additional_sizes = get_option('additional_image_sizes_manager');
    $additional_sizes = !empty($additional_sizes) ? unserialize($additional_sizes) : array();

    if (isset($_POST['submit_additional_sizes'])) {
        check_admin_referer('additional_image_sizes_manager_nonce', 'additional_image_sizes_manager_nonce');

        $new_additional_size = array(
            'name' => sanitize_text_field($_POST['additional_size_name']),
            'max_width' => absint($_POST['additional_size_max_width']),
            'max_height' => absint($_POST['additional_size_max_height']),
            'description' => sanitize_text_field($_POST['additional_size_description']),
        );

        $additional_sizes[] = $new_additional_size;
        update_option('additional_image_sizes_manager', serialize($additional_sizes));
    }

    $edit_index = isset($_GET['edit_additional_size']) ? absint($_GET['edit_additional_size']) : -1;
    $edit_size = isset($additional_sizes[$edit_index]) ? $additional_sizes[$edit_index] : array();

    if (isset($_POST['submit_edit_additional_size'])) {
        check_admin_referer('additional_image_sizes_manager_nonce', 'additional_image_sizes_manager_nonce');

        $edit_size['name'] = sanitize_text_field($_POST['edit_additional_size_name']);
        $edit_size['max_width'] = absint($_POST['edit_additional_size_max_width']);
        $edit_size['max_height'] = absint($_POST['edit_additional_size_max_height']);
        $edit_size['description'] = sanitize_text_field($_POST['edit_additional_size_description']);

        $additional_sizes[$edit_index] = $edit_size;
        update_option('additional_image_sizes_manager', serialize($additional_sizes));

        $edit_index = -1;
        $edit_size = array();

        // Load the settings page again without the query in the URL
        wp_redirect(admin_url('options-general.php?page=additional_image_sizes_manager'));
        exit;
    }

    $delete_index = isset($_GET['delete_additional_size']) ? absint($_GET['delete_additional_size']) : -1;
    $delete_size = isset($additional_sizes[$delete_index]) ? $additional_sizes[$delete_index] : array();

    if (isset($_GET['delete_additional_size'])) {
        $delete_index = absint($_GET['delete_additional_size']);

        if (isset($additional_sizes[$delete_index])) {
            unset($additional_sizes[$delete_index]);
            update_option('additional_image_sizes_manager', serialize($additional_sizes));
        }

        // Load the settings page again without the query in the URL
        wp_redirect(admin_url('options-general.php?page=additional_image_sizes_manager'));
        exit;
    }
    ?>
    <div class="wrap">
        <h1><?php esc_html_e('Additional Image Sizes Manager Settings', 'additional-image-sizes-manager'); ?></h1>
        <p>After making any updates you will need to regenerate your thumbnails using a plugin such as <a href="https://wordpress.org/plugins/regenerate-thumbnails/" target="_blank">Regenerate Thumbnails</a> or using the <a href="https://developer.wordpress.org/cli/commands/media/regenerate/" target="_blank">WP CLI</a> using a command such as <code>wp media regenerate</code>.</p>

        <?php if ($edit_index >= 0) : ?>
            <h2><?php esc_html_e('Edit Additional Image Size', 'additional-image-sizes-manager'); ?></h2>
            <form method="post">
                <?php wp_nonce_field('additional_image_sizes_manager_nonce', 'additional_image_sizes_manager_nonce'); ?>
                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="edit_additional_size_name"><?php esc_html_e('Name', 'additional-image-sizes-manager'); ?></label></th>
                        <td>
                            <input type="text" id="edit_additional_size_name" name="edit_additional_size_name" value="<?php echo esc_attr($edit_size['name']); ?>" maxlength="64" autocomplete="off" required>
                            <p class="description">WordPress reserved names are:</p>
                            <p class="description"><code>thumb</code>, <code>thumbnail</code>, <code>medium</code>, <code>medium_large</code>, <code>large</code>, <code>1536x1536</code>, <code>2048x2048</code>, and <code>full</code>.</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="edit_additional_size_max_width"><?php esc_html_e('Max Width', 'additional-image-sizes-manager'); ?></label></th>
                        <td>
                            <input type="number" id="edit_additional_size_max_width" name="edit_additional_size_max_width" value="<?php echo esc_attr($edit_size['max_width']); ?>" class="small-text" min="1" max="9999" autocomplete="off" required> px
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="edit_additional_size_max_height"><?php esc_html_e('Max Height', 'additional-image-sizes-manager'); ?></label></th>
                        <td>
                            <input type="number" id="edit_additional_size_max_height" name="edit_additional_size_max_height" value="<?php echo esc_attr($edit_size['max_height']); ?>" class="small-text" min="1" max="9999" autocomplete="off" required> px
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="edit_additional_size_description"><?php esc_html_e('Description', 'additional-image-sizes-manager'); ?></label></th>
                        <td>
                            <textarea id="edit_additional_size_description" name="edit_additional_size_description" rows="3" cols="50" maxlength="255" autocomplete="off"><?php echo esc_textarea($edit_size['description']); ?></textarea>
                        </td>
                    </tr>
                </table>
                <p class="submit">
                    <input type="submit" name="submit_edit_additional_size" id="submit_edit_additional_size" class="button button-primary" value="<?php esc_attr_e('Save Changes', 'additional-image-sizes-manager'); ?>">
                    <a class="button" href="<?php echo esc_url(admin_url('options-general.php?page=additional_image_sizes_manager')); ?>"><?php esc_html_e('Cancel', 'additional-image-sizes-manager'); ?></a>
                </p>
            </form>
        <?php else : ?>
            <h2><?php esc_html_e('Add Additional Image Size', 'additional-image-sizes-manager'); ?></h2>
            <form method="post">
                <?php wp_nonce_field('additional_image_sizes_manager_nonce', 'additional_image_sizes_manager_nonce'); ?>
                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="additional_size_name"><?php esc_html_e('Name', 'additional-image-sizes-manager'); ?></label></th>
                        <td>
                            <input type="text" id="additional_size_name" name="additional_size_name" maxlength="64" autocomplete="off" required>
                            <p class="description">WordPress reserved names are:</p>
                            <p class="description"><code>thumb</code>, <code>thumbnail</code>, <code>medium</code>, <code>medium_large</code>, <code>large</code>, <code>1536x1536</code>, <code>2048x2048</code>, and <code>full</code>.</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="additional_size_max_width"><?php esc_html_e('Max Width', 'additional-image-sizes-manager'); ?></label></th>
                        <td>
                            <input type="number" id="additional_size_max_width" name="additional_size_max_width" class="small-text" min="1" max="9999" autocomplete="off" required> px
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="additional_size_max_height"><?php esc_html_e('Max Height', 'additional-image-sizes-manager'); ?></label></th>
                        <td>
                            <input type="number" id="additional_size_max_height" name="additional_size_max_height" class="small-text" min="1" max="9999" autocomplete="off" required> px
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="additional_size_description"><?php esc_html_e('Description', 'additional-image-sizes-manager'); ?></label></th>
                        <td>
                            <textarea id="additional_size_description" name="additional_size_description" rows="3" cols="50" maxlength="255" autocomplete="off"></textarea>
                        </td>
                    </tr>
                </table>
                <p class="submit">
                    <input type="submit" name="submit_additional_sizes" id="submit_additional_sizes" class="button button-primary" value="<?php esc_attr_e('Add Size', 'additional-image-sizes-manager'); ?>">
                </p>
            </form>
        <?php endif; ?>

        <div class="tablenav">
            <div class="alignleft">
                <h2><?php esc_html_e('Additional Image Sizes', 'additional-image-sizes-manager'); ?></h2>
            </div>
            <div class="alignright">
                <span class="displaying-num" style="line-height:2.15384615"><?php echo count($additional_sizes); ?> <?php esc_html_e('sizes total', 'additional-image-sizes-manager'); ?></span>
            </div>
        </div>
        <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" id="additional_image_sizes_manager_list_form">
            <input type="hidden" name="action" value="aism_export_size_data">
            <table class="wp-list-table widefat striped">
                <thead>
                    <tr>
                        <td scope="col" class="manage-column column-cb check-column">
                            <input type="checkbox" id="select_all_checkbox">
                        </td>
                        <th scope="col"><?php esc_html_e('Name', 'additional-image-sizes-manager'); ?></th>
                        <th scope="col"><?php esc_html_e('Max Width', 'additional-image-sizes-manager'); ?></th>
                        <th scope="col"><?php esc_html_e('Max Height', 'additional-image-sizes-manager'); ?></th>
                        <th scope="col"><?php esc_html_e('Description', 'additional-image-sizes-manager'); ?></th>
                        <th scope="col"><?php esc_html_e('Actions', 'additional-image-sizes-manager'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($additional_sizes)) : ?>
                        <tr>
                            <td colspan="6"><?php esc_html_e('No additional image sizes found.', 'additional-image-sizes-manager'); ?></td>
                        </tr>
                    <?php else : ?>
                        <?php foreach ($additional_sizes as $index => $size) : ?>
                            <tr>
                                <th scope="row" class="check-column">
                                    <input type="checkbox" name="selected_sizes[]" value="<?php echo esc_attr($index); ?>">
                                </th>
                                <td><?php echo esc_html($size['name']); ?></td>
                                <td><?php echo esc_html($size['max_width']); ?></td>
                                <td><?php echo esc_html($size['max_height']); ?></td>
                                <td><?php echo esc_html($size['description']); ?></td>
                                <td style="display:flex;gap:.25rem">
                                    <a href="<?php echo esc_url(add_query_arg('edit_additional_size', $index, admin_url('options-general.php?page=additional_image_sizes_manager'))); ?>" class="button"><?php esc_html_e('Edit', 'additional-image-sizes-manager'); ?></a>
                                    <a href="<?php echo esc_url(add_query_arg('delete_additional_size', $index, admin_url('options-general.php?page=additional_image_sizes_manager'))); ?>" class="button" onclick="return confirm('<?php esc_attr_e('Are you sure you want to delete this image size?\n\nIt is recommended to replace any images using this size first.\n\nNOTE: This will not delete any images associated with this size. You will need to regenerate you media files or delete them manually.', 'additional-image-sizes-manager'); ?>');"><?php esc_html_e('Delete', 'additional-image-sizes-manager'); ?></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
            <div class="tablenav">
                <div class="alignright">
                    <?php submit_button(__('Export Selected Sizes', 'additional-image-sizes-manager'), 'secondary', 'export_selected_sizes', false); ?>
                    <script>
                        // Dim the export button if no sizes are selected
                        const exportButton = document.getElementById('export_selected_sizes');
                        const checkboxes = document.querySelectorAll('#additional_image_sizes_manager_list_form input[type="checkbox"]');

                        // Add event listener to checkboxes to enable/disable the export button
                        checkboxes.forEach(checkbox => {
                            checkbox.addEventListener('change', () => {
                                // Check if any checkbox is checked and enable/disable the export button
                                const anyCheckboxChecked = Array.from(checkboxes).some(checkbox => checkbox.checked);
                                exportButton.toggleAttribute('disabled', !anyCheckboxChecked);
                            });
                        });

                        // Set initial state of the export button based on checkbox state
                        const noCheckboxChecked = Array.from(checkboxes).every(checkbox => !checkbox.checked);
                        exportButton.toggleAttribute('disabled', noCheckboxChecked);
                    </script>
                </div>
            </div>
        </form>

        <!-- Import additional sizes from a JSON file -->
        <h2 style="margin-top:2rem"><?php esc_html_e('Import Additional Sizes', 'additional-image-sizes-manager'); ?></h2>
        <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" enctype="multipart/form-data">
            <input type="hidden" name="action" value="aism_import_size_data">
            <table class="form-table">
                <tr>
                    <th scope="row"><?php esc_html_e('JSON File', 'additional-image-sizes-manager'); ?></th>
                    <td>
                        <input type="file" name="additional_sizes_import_file" accept=".json" required>
                        <p class="description"><?php esc_html_e('Select a JSON file containing additional sizes to import.', 'additional-image-sizes-manager'); ?></p>
                    </td>
                </tr>
            </table>
            <?php submit_button(__('Import Sizes', 'additional-image-sizes-manager'), 'primary', 'aism_import_size_data'); ?>
        </form>
    </div>
    <?php
}

// Add additional image sizes table list to the Media Settings page using plain JavaScript
// This avoids a bug when using PHP that would delete all the additional image sizes when 
// "Save Changes" is clicked within the Media Settings options form.
function additional_image_sizes_manager_display_table() {
    $additional_sizes = get_option('additional_image_sizes_manager');
    $additional_sizes = !empty($additional_sizes) ? unserialize($additional_sizes) : array();
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const additionalSizes = <?php echo json_encode($additional_sizes); ?>;
        const table = document.createElement('table');
        table.className = 'wp-list-table widefat striped';
        const thead = table.createTHead();
        const tbody = table.createTBody();
        const headerRow = thead.insertRow();
        const nameHeader = document.createElement('th');
        nameHeader.textContent = '<?php echo esc_html__('Name', 'additional-image-sizes-manager'); ?>';
        headerRow.appendChild(nameHeader);
        const maxWidthHeader = document.createElement('th');
        maxWidthHeader.textContent = '<?php echo esc_html__('Max Width', 'additional-image-sizes-manager'); ?>';
        headerRow.appendChild(maxWidthHeader);
        const maxHeightHeader = document.createElement('th');
        maxHeightHeader.textContent = '<?php echo esc_html__('Max Height', 'additional-image-sizes-manager'); ?>';
        headerRow.appendChild(maxHeightHeader);
        const descriptionHeader = document.createElement('th');
        descriptionHeader.textContent = '<?php echo esc_html__('Description', 'additional-image-sizes-manager'); ?>';
        headerRow.appendChild(descriptionHeader);

        if (additionalSizes.length === 0) {
            const emptyRow = tbody.insertRow();
            const emptyCell = emptyRow.insertCell();
            emptyCell.colSpan = 4;
            emptyCell.textContent = '<?php echo esc_html__('No additional image sizes found.', 'additional-image-sizes-manager'); ?>';
        } else {
            additionalSizes.forEach(function(size) {
                const row = tbody.insertRow();
                const nameCell = row.insertCell();
                nameCell.textContent = size.name;
                const maxWidthCell = row.insertCell();
                maxWidthCell.textContent = size.max_width + 'px';
                const maxHeightCell = row.insertCell();
                maxHeightCell.textContent = size.max_height + 'px';
                const descriptionCell = row.insertCell();
                descriptionCell.textContent = size.description;
            });
        }

        const media_settings_page_wrap = document.querySelector('.options-media-php .wrap');
        if (media_settings_page_wrap) {
            media_settings_page_wrap.appendChild(table);
            const headline = document.createElement('h2');
            headline.textContent = 'Additional Image Sizes';
            media_settings_page_wrap.appendChild(headline);
            media_settings_page_wrap.appendChild(table);

            const textWithLink = document.createElement('p');
            const link = document.createElement('a');
            link.href = 'options-general.php?page=additional_image_sizes_manager';
            link.textContent = 'Manage Additional Image Sizes';
            textWithLink.appendChild(link);
            media_settings_page_wrap.appendChild(textWithLink);
        }
    });
    </script>
    <?php
}
add_action('admin_print_footer_scripts', 'additional_image_sizes_manager_display_table');

// Add custom image size value names to "IMAGE SIZE" dropdown
function aism_custom_image_sizes($sizes)
{
    // Get additional sizes
    $additional_sizes = get_option('additional_image_sizes_manager');

    // Check if the option value is valid and not empty
    if (false !== $additional_sizes && !empty($additional_sizes)) {
        $additional_sizes = unserialize($additional_sizes);

        foreach ($additional_sizes as $size) {
            // Sanitize the name and make it lowercase with underscores for spaces
            $name = sanitize_title($size['name']);
            $name = str_replace(' ', '_', strtolower($name));

            // Add the custom image size to the dropdown
            $sizes[$name] = $size['name'];
        }
    }

    return $sizes;
}
add_filter('image_size_names_choose', 'aism_custom_image_sizes');

// Export the additional image sizes
function aism_export_size_data() {
    if (isset($_POST['export_selected_sizes']) && isset($_POST['selected_sizes'])) {
        $selected_sizes = $_POST['selected_sizes'];

        // Get additional sizes
        $additional_sizes = get_option('additional_image_sizes_manager');
        // Convert the serialized data to an associative array
        $additional_sizes = unserialize($additional_sizes);

        // Prepare the export data
        $export_data = array();
        foreach ($selected_sizes as $index) {
            if (isset($additional_sizes[$index])) {
                $export_data[] = $additional_sizes[$index];
            }
        }

        // Convert the export data to JSON
        $export_data_json = json_encode($export_data);

        // Generate the filename
        $filename = 'additional-image-sizes-export-' . date('YmdHis') . '.json';

        // Set headers for file download
        header('Content-Description: File Transfer');
        header('Content-Disposition: attachment; filename=' . $filename);
        header('Content-Type: application/json');
        header('Content-Length: ' . strlen($export_data_json));

        // Output the export data
        echo $export_data_json;
        exit;
    } else {
        // NOTE: This should not be reachable since the export button is disabled if no sizes are selected
        // No sizes selected, show browser alert
        echo '<script>alert("' . esc_attr__('No sizes selected.', 'additional-image-sizes-manager') . '");</script>';
        echo '<script>window.history.back();</script>';
        return;
    }
}
add_action('admin_post_aism_export_size_data', 'aism_export_size_data');

// Import the additional image sizes
function aism_import_size_data()
{
    if (isset($_FILES['additional_sizes_import_file'])) {
        $import_file = $_FILES['additional_sizes_import_file'];

        // Verify the uploaded file
        if ($import_file['error'] === 0 && $import_file['size'] > 0) {
            $file_data = file_get_contents($import_file['tmp_name']);

            // Parse the JSON data
            $import_data = json_decode($file_data, true);

            if (is_array($import_data)) {
                // Get existing additional sizes
                $additional_sizes = get_option('additional_image_sizes_manager');
                $additional_sizes = !empty($additional_sizes) ? unserialize($additional_sizes) : array();

                // Merge imported sizes with existing sizes
                $additional_sizes = array_merge($additional_sizes, $import_data);

                // Update the additional sizes
                update_option('additional_image_sizes_manager', serialize($additional_sizes));

                // Redirect to the settings page with a success message
                wp_redirect(add_query_arg('imported', 'true', admin_url('admin.php?page=additional_image_sizes_manager')));
                exit;
            }
        }
    }
}
add_action('admin_post_aism_import_size_data', 'aism_import_size_data');
?>
