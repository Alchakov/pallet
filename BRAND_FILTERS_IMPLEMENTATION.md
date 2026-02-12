# Brand Filters Implementation Guide

## Overview
This document outlines the implementation of dynamic brand filters for the Pallet theme. The system allows administrators to create, edit, and manage brand filters through the WordPress admin panel, which are then displayed dynamically on the brand archive page.

## Completed Components

### 1. Admin Brand Ratings Display
- **Status**: ✅ COMPLETED
- **Files Modified**:
  - `parts/loops/loop-brand.php` - Brand rating display with stars in archive cards
  - `templates/brand/single-brand.php` - Brand rating display on single brand page
  - `inc/rating-functions.php` - Rating calculation functions
- **Features**:
  - Average rating calculated from reviews
  - Star display (full and half stars)
  - Review count display
  - Works in both archive and single views

### 2. Brand Filters Admin Management
- **Status**: ✅ COMPLETED
- **Files Created**:
  - `inc/brand-filters-admin.php` - Admin interface for managing filters
  - Included in `functions.php`
- **Features**:
  - Submenu under "Brands" menu in WordPress admin
  - "Manage Filters" page to view existing filters
  - Form to add new filters
  - Uses `brand_feature` taxonomy for storage
  - Admin can easily create new filter categories

### 3. Brand Feature Taxonomy
- **Status**: ✅ COMPLETED
- **Location**: `inc/post_type/PLT_Brand.php`
- **Features**:
  - `brand_feature` taxonomy registered for brand posts
  - Used for dynamic filter management
  - Accessible in WordPress admin
  - Rewrite slug: `brand-feature`

## Remaining Tasks

### Update Archive Brand Template with Dynamic Filters
- **File**: `templates/brand/archive-brand.php`
- **Current State**: Lines 71-120 contain hardcoded filter groups
  - Минимальная поставка (Minimum Order)
  - Кастомизация (Customization)
  - Дополнительные фильтры (Additional Filters)
- **Required Changes**:

Replace lines 71-120 (hardcoded filter section) with the following dynamic code:

```php
<!-- Dynamic Filters Generated from brand_feature Taxonomy -->
<?php
$brand_features = get_terms(array(
    'taxonomy' => 'brand_feature',
    'hide_empty' => false,
    'orderby' => 'name',
));

if (!empty($brand_features) && !is_wp_error($brand_features)) {
    foreach ($brand_features as $feature) {
        ?>
        <div class="brands-filters__group">
            <h4><?php echo esc_html($feature->name); ?></h4>
            <?php
            // Get all brand posts that have this feature
            $featured_brands = get_posts(array(
                'post_type' => 'brand',
                'tax_query' => array(
                    array(
                        'taxonomy' => 'brand_feature',
                        'field' => 'term_id',
                        'terms' => $feature->term_id,
                    ),
                ),
                'fields' => 'ids',
                'posts_per_page' => -1,
            ));
            ?>
            <label class="brands-filters__checkbox">
                <input type="checkbox" 
                       name="brand_features[]" 
                       value="<?php echo esc_attr($feature->slug); ?>"
                       data-feature-id="<?php echo esc_attr($feature->term_id); ?>">
                <span><?php echo esc_html($feature->name); ?> (<?php echo count($featured_brands); ?>)</span>
            </label>
        </div>
        <?php
    }
} else {
    ?>
    <div class="brands-filters__group">
        <p><?php _e('No filters available. Add filters in the admin panel.', 'pallet'); ?></p>
    </div>
    <?php
}
?>
```

## How to Use

### For Administrators

1. **Access Filter Management**:
   - Go to WordPress Admin
   - Navigate to: Brands → Manage Filters

2. **View Existing Filters**:
   - See all existing filters in a table
   - Click "Edit" to modify filter details

3. **Add New Filter**:
   - Enter filter name (e.g., "Retail Sales")
   - Optionally enter a slug (auto-generated from name if left blank)
   - Click "Add Filter"

4. **Assign Filters to Brands**:
   - Edit a Brand post
   - Look for "Фильтры" (Filters) taxonomy box
   - Check the filters that apply to this brand
   - Save the brand

### For Frontend Users

1. **View Filters**:
   - Go to Brand Archive page
   - Filters display dynamically based on admin-created filters
   - Each filter shows count of brands with that feature

2. **Filter Brands**:
   - Check/uncheck filters to refine brand list
   - JavaScript (`custom-filter.js`) handles filter logic
   - Selected filters persist in URL parameters

## JavaScript Integration

**File**: `custom-filter.js`

The script needs to be updated to handle dynamic filters by:

1. Reading filter names from HTML data attributes
2. Building filter queries based on selected filters
3. Sending AJAX requests with filter parameters
4. Updating brand list based on filter results

**Current Implementation**:
- Handles hardcoded filter names (min_order, customization, has_price)
- Uses checkbox names for filter parameters

**Required Update**:
- Make filter parameter names dynamic based on `data-feature-id` attributes
- Support multiple filters from taxonomy

## Database Considerations

No database migrations required. The implementation uses existing WordPress taxonomy system:
- Filters are stored as `brand_feature` terms
- Brand-feature relationships stored in `wp_term_relationships` table
- No custom tables needed

## Testing Checklist

- [ ] Admin can create new filters in "Manage Filters" page
- [ ] New filters appear in Brand edit screen
- [ ] Filters display on brand archive page
- [ ] Filter checkboxes work correctly
- [ ] Filter counts are accurate
- [ ] Multiple filters can be selected
- [ ] Rating stars display correctly in archive cards
- [ ] Rating stars display correctly on single brand page
- [ ] Filter reset button works
- [ ] URL parameters update with selected filters

## Future Enhancements

1. **Filter Groups**: Group related filters under categories
2. **Filter Ordering**: Allow admin to reorder filters via drag-and-drop
3. **Filter Icons**: Add icons to filters for better visual presentation
4. **Multi-level Filters**: Support hierarchical filter categories
5. **Filter Analytics**: Track which filters are most used

## Troubleshooting

### Filters not showing on admin
- Check that `brand-filters-admin.php` is included in `functions.php`
- Clear WordPress caches
- Verify user role has `manage_options` capability

### Filters not displaying on frontend
- Verify `brand_feature` taxonomy is registered for `brand` post type
- Check that filters are assigned to at least one brand
- Review JavaScript console for errors

### Filter functionality not working
- Check `custom-filter.js` is properly enqueued
- Verify AJAX endpoints are accessible
- Check browser console for JavaScript errors

## Files Modified

- `functions.php` - Added include for brand-filters-admin.php
- `inc/brand-filters-admin.php` - Created new file
- `inc/post_type/PLT_Brand.php` - Brand feature taxonomy already registered
- `templates/brand/archive-brand.php` - Awaiting dynamic filter implementation
- `custom-filter.js` - Awaiting JavaScript updates for dynamic filters
