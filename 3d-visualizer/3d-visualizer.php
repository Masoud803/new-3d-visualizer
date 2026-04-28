<?php
/**
 * Plugin Name: 3D Visualizer
 * Plugin URI:  https://example.com
 * Description: A 3D visualizer plugin using Three.js, supporting GLB/GLTF models, background images, and WooCommerce integration.
 * Version:     1.0.0
 * Author:      Jules
 * License:     GPLv2 or later
 * Text Domain: 3d-visualizer
 */

if ( ! defined( 'ABSPATH' ) ) {
    die; // Exit if accessed directly.
}

define( 'TDV_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'TDV_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

/**
 * Allow .glb and .gltf uploads in the WordPress Media Library
 */
function tdv_allow_3d_mimes( $mimes ) {
    $mimes['glb']  = 'model/gltf-binary';
    $mimes['gltf'] = 'model/gltf+json';
    return $mimes;
}
add_filter( 'upload_mimes', 'tdv_allow_3d_mimes' );

/**
 * Enqueue Admin Scripts & Styles
 */
function tdv_enqueue_admin_scripts( $hook ) {
    if ( strpos( $hook, '3d-visualizer' ) === false ) {
        return;
    }

    wp_enqueue_style( 'tdv-admin-style', TDV_PLUGIN_URL . 'assets/css/admin.css', array(), '1.0.0' );
    wp_enqueue_script( 'tdv-admin-script', TDV_PLUGIN_URL . 'assets/js/admin.js', array( 'jquery' ), '1.0.0', true );
}
add_action( 'admin_enqueue_scripts', 'tdv_enqueue_admin_scripts' );

/**
 * Enqueue Frontend Scripts & Styles
 */
function tdv_enqueue_frontend_scripts() {
    wp_register_style( 'tdv-frontend-style', TDV_PLUGIN_URL . 'assets/css/frontend.css', array(), '1.0.0' );

    // Vendor scripts
    wp_register_script( 'tdv-three-js', TDV_PLUGIN_URL . 'assets/vendor/three.min.js', array(), '1.0.0', true );
    wp_register_script( 'tdv-gltf-loader', TDV_PLUGIN_URL . 'assets/vendor/GLTFLoader.js', array( 'tdv-three-js' ), '1.0.0', true );
    wp_register_script( 'tdv-orbit-controls', TDV_PLUGIN_URL . 'assets/vendor/OrbitControls.js', array( 'tdv-three-js' ), '1.0.0', true );

    // Main visualizer script
    wp_register_script( 'tdv-visualizer-js', TDV_PLUGIN_URL . 'assets/js/visualizer.js', array( 'jquery', 'tdv-three-js', 'tdv-gltf-loader', 'tdv-orbit-controls' ), '1.0.0', true );
}
add_action( 'wp_enqueue_scripts', 'tdv_enqueue_frontend_scripts' );

/**
 * Register Custom Post Type for Visualizers
 */
function tdv_register_cpt() {
    $labels = array(
        'name'                  => _x( 'Visualizers', 'Post Type General Name', '3d-visualizer' ),
        'singular_name'         => _x( 'Visualizer', 'Post Type Singular Name', '3d-visualizer' ),
        'menu_name'             => __( '3D Visualizer', '3d-visualizer' ),
        'name_admin_bar'        => __( 'Visualizer', '3d-visualizer' ),
        'add_new'               => __( 'Add New Visualizer', '3d-visualizer' ),
        'add_new_item'          => __( 'Add New Visualizer', '3d-visualizer' ),
        'new_item'              => __( 'New Visualizer', '3d-visualizer' ),
        'edit_item'             => __( 'Edit Visualizer', '3d-visualizer' ),
        'view_item'             => __( 'View Visualizer', '3d-visualizer' ),
        'all_items'             => __( 'All Visualizers', '3d-visualizer' ),
    );
    $args = array(
        'label'                 => __( 'Visualizer', '3d-visualizer' ),
        'labels'                => $labels,
        'supports'              => array( 'title' ),
        'public'                => false,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'menu_icon'             => 'dashicons-format-image',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => false,
        'can_export'            => true,
        'has_archive'           => false,
        'exclude_from_search'   => true,
        'publicly_queryable'    => false,
        'rewrite'               => false,
        'show_in_rest'          => true,
    );
    register_post_type( 'tdv_visualizer', $args );
}
add_action( 'init', 'tdv_register_cpt', 0 );

/**
 * Register Submenus for Settings & System Info (following designs)
 */
function tdv_add_admin_pages() {
    add_submenu_page(
        'edit.php?post_type=tdv_visualizer',
        __( 'Global Settings', '3d-visualizer' ),
        __( 'Global Settings', '3d-visualizer' ),
        'manage_options',
        'tdv_global_settings',
        'tdv_global_settings_page_html'
    );
}
add_action( 'admin_menu', 'tdv_add_admin_pages' );

/**
 * Add Meta Boxes for Visualizer configuration
 */
function tdv_add_meta_boxes() {
    add_meta_box(
        'tdv_models_meta_box',
        __( 'Models & Environments', '3d-visualizer' ),
        'tdv_models_meta_box_html',
        'tdv_visualizer',
        'normal',
        'high'
    );

    add_meta_box(
        'tdv_placement_meta_box',
        __( 'Configuration & Placement', '3d-visualizer' ),
        'tdv_placement_meta_box_html',
        'tdv_visualizer',
        'side',
        'default'
    );
}
add_action( 'add_meta_boxes', 'tdv_add_meta_boxes' );

function tdv_models_meta_box_html( $post ) {
    wp_nonce_field( 'tdv_save_meta_box_data', 'tdv_meta_box_nonce' );

    $models_json = get_post_meta( $post->ID, '_tdv_models', true );
    if ( empty( $models_json ) ) {
        $models_json = '[]';
    }

    $default_bg = get_post_meta( $post->ID, '_tdv_default_bg', true );

    ?>
    <div class="tdv-admin-wrap">
        <p>
            <label for="tdv_default_bg"><strong><?php _e( 'Default Environment Background Image URL', '3d-visualizer' ); ?></strong></label><br/>
            <input type="text" id="tdv_default_bg" name="tdv_default_bg" value="<?php echo esc_attr( $default_bg ); ?>" style="width:100%;" />
            <button type="button" class="button tdv-upload-btn" data-target="tdv_default_bg"><?php _e( 'Choose Image', '3d-visualizer' ); ?></button>
        </p>
        <hr/>
        <h3><?php _e( '3D Models', '3d-visualizer' ); ?></h3>
        <div id="tdv_models_container"></div>
        <button type="button" class="button button-primary" id="tdv_add_model_btn"><?php _e( '+ Add Model', '3d-visualizer' ); ?></button>

        <input type="hidden" name="tdv_models" id="tdv_models_input" value="<?php echo esc_attr( $models_json ); ?>" />
    </div>

    <script>
    jQuery(document).ready(function($){
        var models = <?php echo $models_json; ?>;
        var container = $('#tdv_models_container');

        function renderModels() {
            container.empty();
            models.forEach(function(model, index) {
                var html = '<div class="tdv-model-item" style="border:1px solid #ccc; padding:10px; margin-bottom:10px;">';
                html += '<h4>Model ' + (index + 1) + ' <button type="button" class="button tdv-remove-model" data-index="'+index+'" style="float:right; color:red;">Remove</button></h4>';
                html += '<p><label>Name: <input type="text" class="tdv-model-name" data-index="'+index+'" value="'+(model.name || '')+'" /></label></p>';
                html += '<p><label>GLB/GLTF File URL: <br/><input type="text" class="tdv-model-file" data-index="'+index+'" value="'+(model.file || '')+'" style="width:80%;" /> <button type="button" class="button tdv-upload-btn-model" data-index="'+index+'">Choose File</button></label></p>';
                html += '<p><label>Specific Background Image URL (Overrides Default): <br/><input type="text" class="tdv-model-bg" data-index="'+index+'" value="'+(model.bg || '')+'" style="width:80%;" /> <button type="button" class="button tdv-upload-btn-bg" data-index="'+index+'">Choose Image</button></label></p>';
                html += '</div>';
                container.append(html);
            });
            $('#tdv_models_input').val(JSON.stringify(models));
        }

        renderModels();

        $('#tdv_add_model_btn').on('click', function(e){
            e.preventDefault();
            models.push({ name: '', file: '', bg: '' });
            renderModels();
        });

        container.on('click', '.tdv-remove-model', function(e){
            e.preventDefault();
            var index = $(this).data('index');
            models.splice(index, 1);
            renderModels();
        });

        container.on('change', 'input', function(){
            var index = $(this).data('index');
            if($(this).hasClass('tdv-model-name')) models[index].name = $(this).val();
            if($(this).hasClass('tdv-model-file')) models[index].file = $(this).val();
            if($(this).hasClass('tdv-model-bg')) models[index].bg = $(this).val();
            $('#tdv_models_input').val(JSON.stringify(models));
        });

        // Media Uploader Logic will be handled in admin.js
    });
    </script>
    <?php
}

function tdv_placement_meta_box_html( $post ) {
    $placement = get_post_meta( $post->ID, '_tdv_placement', true );
    $target_ids = get_post_meta( $post->ID, '_tdv_target_ids', true );

    ?>
    <p>
        <strong>Shortcode:</strong><br/>
        <code>[3d_visualizer id="<?php echo $post->ID; ?>"]</code>
    </p>
    <hr/>
    <p>
        <label for="tdv_placement"><strong><?php _e( 'WooCommerce / Page Placement', '3d-visualizer' ); ?></strong></label><br/>
        <select id="tdv_placement" name="tdv_placement" style="width:100%;">
            <option value="none" <?php selected( $placement, 'none' ); ?>><?php _e( 'None (Shortcode Only)', '3d-visualizer' ); ?></option>
            <option value="replace_gallery" <?php selected( $placement, 'replace_gallery' ); ?>><?php _e( 'Replace WooCommerce Gallery', '3d-visualizer' ); ?></option>
            <option value="before_add_to_cart" <?php selected( $placement, 'before_add_to_cart' ); ?>><?php _e( 'Before Add to Cart', '3d-visualizer' ); ?></option>
            <option value="after_add_to_cart" <?php selected( $placement, 'after_add_to_cart' ); ?>><?php _e( 'After Add to Cart', '3d-visualizer' ); ?></option>
            <option value="after_short_description" <?php selected( $placement, 'after_short_description' ); ?>><?php _e( 'After Short Description', '3d-visualizer' ); ?></option>
        </select>
    </p>
    <p>
        <label for="tdv_target_ids"><strong><?php _e( 'Target Post/Product IDs (comma separated)', '3d-visualizer' ); ?></strong></label><br/>
        <input type="text" id="tdv_target_ids" name="tdv_target_ids" value="<?php echo esc_attr( $target_ids ); ?>" style="width:100%;" placeholder="e.g., 12, 45, 99" />
        <br/><small><?php _e( 'Leave empty to apply to ALL products/pages (not recommended).', '3d-visualizer' ); ?></small>
    </p>
    <?php
}

/**
 * Save Meta Box Data
 */
function tdv_save_meta_box_data( $post_id ) {
    if ( ! isset( $_POST['tdv_meta_box_nonce'] ) ) {
        return;
    }
    if ( ! wp_verify_nonce( $_POST['tdv_meta_box_nonce'], 'tdv_save_meta_box_data' ) ) {
        return;
    }
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    if ( isset( $_POST['tdv_models'] ) ) {
        update_post_meta( $post_id, '_tdv_models', sanitize_text_field( wp_unslash( $_POST['tdv_models'] ) ) );
    }
    if ( isset( $_POST['tdv_default_bg'] ) ) {
        update_post_meta( $post_id, '_tdv_default_bg', esc_url_raw( wp_unslash( $_POST['tdv_default_bg'] ) ) );
    }
    if ( isset( $_POST['tdv_placement'] ) ) {
        update_post_meta( $post_id, '_tdv_placement', sanitize_text_field( wp_unslash( $_POST['tdv_placement'] ) ) );
    }
    if ( isset( $_POST['tdv_target_ids'] ) ) {
        update_post_meta( $post_id, '_tdv_target_ids', sanitize_text_field( wp_unslash( $_POST['tdv_target_ids'] ) ) );
    }
}
add_action( 'save_post_tdv_visualizer', 'tdv_save_meta_box_data' );

/**
 * Global Settings Page HTML
 */
function tdv_global_settings_page_html() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }
    if ( isset( $_POST['tdv_save_global'] ) ) {
        check_admin_referer( 'tdv_global_settings_action' );
        update_option( 'tdv_container_width', sanitize_text_field( $_POST['tdv_container_width'] ) );
        update_option( 'tdv_container_height', sanitize_text_field( $_POST['tdv_container_height'] ) );
        echo '<div class="notice notice-success is-dismissible"><p>Settings saved.</p></div>';
    }

    $width = get_option( 'tdv_container_width', '100%' );
    $height = get_option( 'tdv_container_height', '500px' );
    ?>
    <div class="wrap">
        <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
        <form method="post" action="">
            <?php wp_nonce_field( 'tdv_global_settings_action' ); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Visualizer Container Width</th>
                    <td><input type="text" name="tdv_container_width" value="<?php echo esc_attr( $width ); ?>" /> (e.g. 100%, 800px)</td>
                </tr>
                <tr valign="top">
                    <th scope="row">Visualizer Container Height</th>
                    <td><input type="text" name="tdv_container_height" value="<?php echo esc_attr( $height ); ?>" /> (e.g. 500px, 60vh)</td>
                </tr>
            </table>
            <?php submit_button( 'Save Settings', 'primary', 'tdv_save_global' ); ?>
        </form>
    </div>
    <?php
}

/**
 * Visualizer Shortcode Handler
 */
function tdv_visualizer_shortcode( $atts ) {
    $atts = shortcode_atts( array(
        'id' => 0,
    ), $atts, '3d_visualizer' );

    $post_id = intval( $atts['id'] );
    if ( ! $post_id || get_post_type( $post_id ) !== 'tdv_visualizer' ) {
        return '<!-- Invalid 3D Visualizer ID -->';
    }

    $models_json = get_post_meta( $post_id, '_tdv_models', true );
    $default_bg  = get_post_meta( $post_id, '_tdv_default_bg', true );

    if ( empty( $models_json ) || $models_json === '[]' ) {
        return '<!-- No 3D Models Configured -->';
    }

    // Enqueue scripts
    wp_enqueue_script( 'tdv-visualizer-js' );
    wp_enqueue_style( 'tdv-frontend-style' );

    // Pass data to JS
    wp_localize_script( 'tdv-visualizer-js', 'tdvData_' . $post_id, array(
        'models'    => json_decode( $models_json, true ),
        'defaultBg' => $default_bg
    ) );

    $width = get_option( 'tdv_container_width', '100%' );
    $height = get_option( 'tdv_container_height', '500px' );

    ob_start();
    ?>
    <div class="tdv-container" id="tdv-container-<?php echo esc_attr( $post_id ); ?>" style="width: <?php echo esc_attr( $width ); ?>; height: <?php echo esc_attr( $height ); ?>; position: relative;">
        <!-- Canvas wrapper -->
        <div class="tdv-canvas-wrapper" style="width: 100%; height: 100%; position: absolute; top:0; left:0;"></div>

        <!-- Loading overlay -->
        <div class="tdv-loading-overlay" style="position: absolute; top:0; left:0; width:100%; height:100%; background:rgba(255,255,255,0.8); display:flex; justify-content:center; align-items:center; z-index:10;">
            <span>Loading 3D Model...</span>
        </div>

        <!-- Model Switcher UI (if multiple models) -->
        <?php
        $models = json_decode( $models_json, true );
        if ( count( $models ) > 1 ):
        ?>
        <div class="tdv-model-switcher" style="position: absolute; bottom: 20px; left: 50%; transform: translateX(-50%); z-index: 11; display:flex; gap:10px; background: rgba(0,0,0,0.5); padding: 10px; border-radius: 5px;">
            <?php foreach ( $models as $index => $model ): ?>
                <button type="button" class="tdv-switch-btn" data-index="<?php echo esc_attr( $index ); ?>" data-target="tdv-container-<?php echo esc_attr( $post_id ); ?>">
                    <?php echo esc_html( !empty($model['name']) ? $model['name'] : 'Model ' . ($index + 1) ); ?>
                </button>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof initTdvVisualizer === 'function') {
                initTdvVisualizer('tdv-container-<?php echo esc_attr( $post_id ); ?>', tdvData_<?php echo esc_attr( $post_id ); ?>);
            }
        });
    </script>
    <?php
    return ob_get_clean();
}
add_shortcode( '3d_visualizer', 'tdv_visualizer_shortcode' );

/**
 * Handle Automatic Placements (WooCommerce & Pages)
 */
function tdv_get_applicable_visualizers( $post_id ) {
    $visualizers = get_posts( array(
        'post_type'      => 'tdv_visualizer',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
    ) );

    $applicable = array();

    foreach ( $visualizers as $vis ) {
        $placement = get_post_meta( $vis->ID, '_tdv_placement', true );
        if ( ! $placement || $placement === 'none' ) continue;

        $target_ids_str = get_post_meta( $vis->ID, '_tdv_target_ids', true );
        if ( empty( trim( $target_ids_str ) ) ) {
            $applicable[] = array( 'id' => $vis->ID, 'placement' => $placement );
        } else {
            $target_ids = array_map( 'trim', explode( ',', $target_ids_str ) );
            if ( in_array( (string)$post_id, $target_ids, true ) ) {
                $applicable[] = array( 'id' => $vis->ID, 'placement' => $placement );
            }
        }
    }

    return $applicable;
}

// Helper function to render shortcodes based on hook
function tdv_render_placements( $position ) {
    if ( ! is_singular() ) return;
    $post_id = get_the_ID();
    $visualizers = tdv_get_applicable_visualizers( $post_id );

    foreach ( $visualizers as $vis ) {
        if ( $vis['placement'] === $position ) {
            echo do_shortcode( '[3d_visualizer id="' . $vis['id'] . '"]' );
        }
    }
}

// Hook into specific WooCommerce locations
add_action( 'woocommerce_before_add_to_cart_form', function() { tdv_render_placements('before_add_to_cart'); }, 15 );
add_action( 'woocommerce_after_add_to_cart_form', function() { tdv_render_placements('after_add_to_cart'); }, 15 );
add_action( 'woocommerce_after_single_product_summary', function() { tdv_render_placements('after_short_description'); }, 5 );

/**
 * Replace WooCommerce Gallery if placement is set to 'replace_gallery'
 */
function tdv_replace_woo_gallery( $html, $post_id ) {
    if ( ! is_product() ) return $html;

    $visualizers = tdv_get_applicable_visualizers( $post_id );
    foreach ( $visualizers as $vis ) {
        if ( $vis['placement'] === 'replace_gallery' ) {
            // Found a visualizer set to replace gallery for this product
            return do_shortcode( '[3d_visualizer id="' . $vis['id'] . '"]' );
        }
    }

    return $html;
}
add_filter( 'woocommerce_single_product_image_thumbnail_html', 'tdv_replace_woo_gallery', 100, 2 );

// For the main image replacement, some themes/woo versions use this template path override or hooks
function tdv_override_product_images() {
    if ( ! is_product() ) return;
    $post_id = get_the_ID();
    $visualizers = tdv_get_applicable_visualizers( $post_id );
    $should_replace = false;
    $vis_id = 0;

    foreach ( $visualizers as $vis ) {
        if ( $vis['placement'] === 'replace_gallery' ) {
            $should_replace = true;
            $vis_id = $vis['id'];
            break;
        }
    }

    if ( $should_replace ) {
        remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20 );
        add_action( 'woocommerce_before_single_product_summary', function() use ($vis_id) {
            echo '<div class="woocommerce-product-gallery">';
            echo do_shortcode( '[3d_visualizer id="' . $vis_id . '"]' );
            echo '</div>';
        }, 20 );
    }
}
add_action( 'wp', 'tdv_override_product_images' );
