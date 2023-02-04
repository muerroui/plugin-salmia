<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

$middle_infos = stm_get_car_archive_listings();

if (!empty($middle_infos)): ?>
    <?php foreach ($middle_infos as $middle_info):

        $data_meta = get_post_meta(get_the_ID(), $middle_info['slug'], true);
        $data_value = '';

        //Item option has 3 views - numeric (single value in post meta, single value in term meta and multiply terms).
        if (!empty($data_meta) and $data_meta != 'none' and $middle_info['slug'] != 'price'):
            if (!empty($middle_info['numeric']) and $middle_info['numeric']):
                $data_value = ucfirst($data_meta);
            else:
                $data_meta_array = explode(',', $data_meta);
                $data_value = array();
                foreach (get_the_terms(get_the_ID(), $middle_info['slug']) as $_term) {
                    $data_value[] = $_term->name;
                }
            endif;
        endif; ?>
        <?php if (!empty($data_value) and $data_value != ''): ?>
        <?php if ($middle_info['slug'] != 'price' and !empty($data_meta)): ?>
            <div class="meta-middle-unit <?php if (!empty($middle_info['font'])) {
                echo esc_attr('font-exists');
            } ?> <?php echo esc_attr($middle_info['slug']); ?>">
                <div class="meta-middle-unit-top">
                    <?php if (!empty($middle_info['font'])): ?>
                        <div class="icon"><i class="<?php echo esc_attr($middle_info['font']); ?>"></i></div>
                    <?php endif; ?>
                    <div class="name"><?php esc_html_e($middle_info['single_name'], 'stm_vehicles_listing'); ?></div>
                </div>

                <div class="value h5">
                    <?php
                    if (is_array($data_value)) {
                        echo esc_attr(implode(', ', $data_value));
                    } else {
                        echo esc_attr($data_value);
                    }
                    ?>
                </div>
            </div>
        <?php endif; ?>
    <?php endif; ?>
    <?php endforeach; ?>
<?php endif; ?>