<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
// Add options page to menu
function add_theme_menu_item_import_xml()
{
    add_submenu_page(
        'edit.php?post_type=listings',
        __("Import from XML", 'stm_vehicles_listing'),
        __("Import from XML", 'stm_vehicles_listing'),
        'manage_options',
        'stm_xml_import',
        'stm_vehicle_import_xml'
    );
}

add_action("admin_menu", "add_theme_menu_item_import_xml");


function stm_array($array)
{
    echo '<pre>';
    print_r($array);
    echo '</pre>';
}

function stm_vehicle_import_xml()
{

    $stm_get_taxonomies = stm_get_taxonomies();

    $error_type = '';

    $default_post_fields = array(
        'Post Title',
        'Post Content',
        'Post Excerpt',
        'Post Status',
    );

    if (!empty($_FILES['import_upload'])) {
        if ($_FILES['import_upload']['type'] != 'text/xml') {
            $error_type = esc_html__('Please, upload file with .xml format');
        }

        if (empty($error_type)) {

            $xml_file = fopen($_FILES['import_upload']['tmp_name'], 'r+');

            $tmp_lines = array();
            $lines = array();
            $xml = simplexml_load_file($_FILES['import_upload']['tmp_name']);

            $xml_keys = get_object_vars($xml);
            reset($xml_keys);
            $first_key = key($xml_keys);

            foreach ($xml->$first_key as $car) {
                $tmp_lines[] = get_object_vars($car);
            }


            foreach ($tmp_lines as $line_key => $line_value) {
                if (!empty($line_value)) {
                    foreach ($line_value as $line_node_key => $line_node_value) {
                        if (is_object($line_node_value)) {
                            $obj_nodes = get_object_vars($line_node_value);
                            if (!empty($obj_nodes)) {
                                /*
foreach ($obj_nodes as $obj_node_key=>$obj_node_value) {
                                    $lines[$line_key][$line_node_key.'_'.$obj_node_key] = $obj_node_value;
                                }
*/
                            }
                        } else {
                            $lines[$line_key][$line_node_key] = $line_node_value;
                        }
                    }
                }
            }

            update_option('parsed_xml', $lines);

        }
    }

    ?>


    <div id="col-container">
        <h2 class="clear"><?php _e('Import from XML', 'stm_vehicles_listing'); ?></h2>

        <div class="stm_xml_file_upload form-wrap">

            <!-- Uploading file -->
            <h4><?php esc_html_e('Load XML from your local files'); ?></h4>
            <form id="upload_xml_form" method="post" enctype="multipart/form-data" class="wp-upload-form"
                  action="<?php echo remove_query_arg("error"); ?>" name="stm_import_upload">
                <label class="screen-reader-text" for="import_upload"><?php esc_html_e("Listing file"); ?></label>
                <input type="file" id="import_upload" name="import_upload">
                <input type="submit" name="import_submit_auto" id="upload_xml_motors" class="button" value="Import Now">
            </form>


            <!-- If not xml uploaded -->
            <?php if (!empty($error_type)): ?>
                <p class="danger" style="color:red;"><?php esc_html_e($error_type); ?></p>
            <?php endif; ?>

            <!-- Show ui for the xml parsing way -->
            <?php if (!empty($lines)): ?>
                <div class="source">
                    <?php $i = 0;
                    foreach ($lines[0] as $key => $line): ?>
                        <?php if (!is_object($line)): ?>
                            <div class="item" data-key="<?php echo esc_attr($key); ?>">
                                <span class="closer">x</span><?php echo ucfirst($key); ?>
                            </div>
                        <?php endif; ?>

                        <?php $i++; endforeach; ?>
                </div>

                <form method="post" action="<?php echo remove_query_arg("error"); ?>" name="stm_xml_import">
                    <div class="target">
                        <?php foreach ($default_post_fields as $key => $default_post_field): ?>
                            <div class="target-unit">
                                <?php echo esc_attr($default_post_field); ?>
                                <input type="hidden"
                                       name="stm_xml_field[<?php echo str_replace('-', '_', sanitize_title($default_post_field)); ?>]"
                                       value=""/>
                                <div class="empty"></div>
                            </div>
                        <?php endforeach; ?>

                        <?php if (!empty($stm_get_taxonomies)): ?>
                            <?php foreach ($stm_get_taxonomies as $key => $taxonomy): ?>
                                <?php if ($taxonomy == 'price'): ?>
                                    <div class="target-unit">
                                        <?php esc_html_e('Price'); ?>
                                        <input type="hidden" name="stm_xml_field[price]" value=""/>
                                        <div class="empty"></div>
                                    </div>
                                    <div class="target-unit">
                                        <?php esc_html_e('Sale Price'); ?>
                                        <input type="hidden" name="stm_xml_field[sale_price]" value=""/>
                                        <div class="empty"></div>
                                    </div>
                                <?php else: ?>
                                    <div class="target-unit">
                                        <?php echo esc_attr($key); ?>
                                        <input type="hidden" name="stm_xml_field[<?php echo esc_attr($taxonomy); ?>]"
                                               value=""/>
                                        <div class="empty"></div>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <div class="clear"></div>

                    <button class="button" type="submit" class="start_import"><?php esc_html_e('Import'); ?></button>
                </form>

            <?php endif; ?>


            <!-- Parse xml and create new posts -->
            <?php

            $parsed_xml = get_option('parsed_xml');

            if (!empty($parsed_xml) and !empty($_POST['stm_xml_field'])) {
                $error = false;

                $lines = $parsed_xml;

                $user_choices = sanitize_text_field($_POST['stm_xml_field']);

                $default_post_fields = array('post_title', 'post_content', 'post_excerpt', 'post_status');

                $filtered = $user_choices;

                foreach ($default_post_fields as $default_post_field) {
                    unset($filtered[$default_post_field]);
                }

                if (empty($user_choices['post_title'])) {
                    $error = true; ?>
                    <p class="danger" style="color:red;"><?php esc_html_e("Post title is required field"); ?></p>
                    <?php
                }

                if (empty($user_choices['post_content'])) {
                    $error = true; ?>
                    <p class="danger" style="color:red;"><?php esc_html_e("Post content is required field"); ?></p>
                    <?php
                }

                if (empty($user_choices['post_excerpt'])) {
                    $error = true; ?>
                    <p class="danger" style="color:red;"><?php esc_html_e("Post excerpt is required field"); ?></p>
                    <?php
                }


                if (!$error) {
                    foreach ($lines as $line) {

                        $title = 'No title';
                        $post_content = 'No content';
                        $post_excerpt = 'No excerpt';
                        $post_status = 'draft';

                        if (isset($user_choices['post_title']) or $user_choices['post_title'] == '0') {
                            $title = $line[$user_choices['post_title']];
                        }

                        if (isset($user_choices['post_content']) or $user_choices['post_content'] == '0') {
                            $post_content = $line[$user_choices['post_content']];
                        }

                        if (isset($user_choices['post_excerpt']) or $user_choices['post_excerpt'] == '0') {
                            $post_excerpt = $line[$user_choices['post_excerpt']];
                        }

                        if (!empty($user_choices['post_status'])) {
                            $post_status = $line[$user_choices['post_status']];
                        }

                        $post_to_insert = array(
                            'post_title' => $title,
                            'post_content' => $post_content,
                            'post_excerpt' => $post_excerpt,
                            'post_status' => $post_status,
                            'post_type' => 'listings',
                        );


                        $post_to_insert_id = wp_insert_post($post_to_insert);

                        if (!empty($filtered)) {
                            foreach ($filtered as $key => $filter_value) {
                                if (!empty($filter_value)) {
                                    if ($key != 'sale_price') {
                                        $numeric = stm_get_taxonomies_with_type($key);
                                        if (!empty($numeric) and !empty($numeric['numeric']) and $numeric['numeric']) {
                                            update_post_meta($post_to_insert_id, $key, sanitize_title($line[$filter_value]));
                                        } else {
                                            wp_add_object_terms($post_to_insert_id, $line[$filter_value], $key, true);
                                            update_post_meta($post_to_insert_id, $key, sanitize_title($line[$filter_value]));
                                        }
                                    } else {
                                        update_post_meta($post_to_insert_id, $key, sanitize_title($line[$filter_value]));
                                    }
                                }
                            }
                        }

                    } ?>
                    <p class="success" style="color:green"><?php esc_html_e('XML imported'); ?></p>
                    <?php
                    update_option('parsed_xml', '');
                }


            }
            ?>


        </div>
    </div>
<?php }