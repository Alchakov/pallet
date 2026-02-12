<?php
/**
 * Brand Filters Admin Management
 * Provides an admin interface for managing dynamic brand filters
 */

/*REMOVED: // Add admin menu for brand filters management */

/* add_action('admin_menu', function() {
    add_submenu_page(
        'edit.php?post_type=brand',
        'Manage Filters',
        'Manage Filters',
        'manage_options',
        'brand-filters-management',
        'brand_filters_management_page'
    );
}); */


// Admin page callback
function brand_filters_management_page() {
    ?>
    <div class="wrap">
        <h1>Brand Filters Management</h1>
        <p>Manage filters for brand filtering on the archive page.</p>
        
        <div class="filters-section">
            <h2>Available Filters</h2>
            <?php
            $filters = get_terms(array(
                'taxonomy' => 'brand_feature',
                'hide_empty' => false,
            ));
            
            if (!empty($filters)) {
                echo '<table class="wp-list-table widefat striped">';
                echo '<thead><tr><th>Filter Name</th><th>Slug</th><th>Count</th><th>Actions</th></tr></thead>';
                echo '<tbody>';
                
                foreach ($filters as $filter) {
                    echo '<tr>';
                    echo '<td>' . esc_html($filter->name) . '</td>';
                    echo '<td>' . esc_html($filter->slug) . '</td>';
                    echo '<td>' . $filter->count . '</td>';
                    echo '<td>';
                    echo '<a href="' . get_edit_term_link($filter, 'brand_feature') . '" class="button">Edit</a> ';
                    echo '</td>';
                    echo '</tr>';
                }
                
                echo '</tbody>';
                echo '</table>';
            } else {
                echo '<p>No filters found. Add new filters below.</p>';
            }
            ?>
        </div>
        
        <div class="filters-add-section">
            <h2>Add New Filter</h2>
            <form method="post" action="<?php echo admin_url('admin.php?page=brand-filters-management'); ?>">
                <?php wp_nonce_field('brand_filter_nonce'); ?>
                
                <table class="form-table">
                    <tr>
                        <th><label for="filter_name">Filter Name</label></th>
                        <td>
                            <input type="text" id="filter_name" name="filter_name" class="regular-text" required />
                            <p class="description">The name of the filter (e.g., "Minimum Order", "Customization")</p>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="filter_slug">Filter Slug</label></th>
                        <td>
                            <input type="text" id="filter_slug" name="filter_slug" class="regular-text" />
                            <p class="description">Auto-generated from the filter name if left blank</p>
                        </td>
                    </tr>
                </table>
                
                <submit type="submit" name="brand_filter_submit" class="button button-primary">Add Filter</submit>
            </form>
        </div>
    </div>
    <?php
}

// Handle form submission
add_action('admin_init', function() {
    if (isset($_POST['brand_filter_submit']) && wp_verify_nonce($_POST['_wpnonce'], 'brand_filter_nonce')) {
        if (current_user_can('manage_options') && !empty($_POST['filter_name'])) {
            $filter_name = sanitize_text_field($_POST['filter_name']);
            $filter_slug = sanitize_text_field($_POST['filter_slug']);
            
            // If slug is empty, generate from name
            if (empty($filter_slug)) {
                $filter_slug = sanitize_title($filter_name);
            }
            
            // Insert the term
            $result = wp_insert_term($filter_name, 'brand_feature', array(
                'slug' => $filter_slug
            ));
            
            if (!is_wp_error($result)) {
                wp_redirect(add_query_arg('filter_added', 'true', admin_url('admin.php?page=brand-filters-management')));
                exit;
            }
        }
    }
});

// Display success message
add_action('admin_notices', function() {
    if (isset($_GET['filter_added'])) {
        echo '<div class="notice notice-success is-dismissible"><p>Filter added successfully!</p></div>';
    }
});
