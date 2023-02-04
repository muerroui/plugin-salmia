<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

$data = stm_get_single_car_listings();
$post_id = get_the_ID();
$vin_num = get_post_meta(get_the_id(), 'vin_number', true);
?>

<?php if (!empty($data)): ?>
    <div class="single-car-data">
        <?php
        /*If automanager, and no image in admin, set default image carfax*/
        $history_link_1 = get_post_meta(get_the_ID(), 'history_link', true);
        $certified_logo_1 = get_post_meta(get_the_ID(), 'certified_logo_1', true);

        if (!empty($certified_logo_1)):
            $certified_logo_1 = wp_get_attachment_image_src($certified_logo_1, 'stm-img-255-135');

            if (!empty($certified_logo_1[0])) {
                $certified_logo_1 = $certified_logo_1[0]; ?>
                <div class="text-center stm-single-car-history-image">
                    <a href="<?php echo esc_url($history_link_1); ?>" target="_blank">
                        <img src="<?php echo esc_url($certified_logo_1); ?>" class="img-responsive dp-in"/>
                    </a>
                </div>
            <?php }
        endif;
        ?>
        <table>
            <?php foreach ($data as $data_value): ?>
	            <?php
	            $affix = '';
	            if(!empty($data_value['number_field_affix'])) {
		            $affix = $data_value['number_field_affix'];
	            }
	            ?>
	            
                <?php if ($data_value['slug'] != 'price'): ?>
                    <?php $data_meta = get_post_meta($post_id, $data_value['slug'], true); ?>
                    <?php if (!empty($data_meta) and $data_meta != 'none'): ?>
                        <tr>
                            <td class="t-label"><?php esc_html_e($data_value['single_name'], 'stm_vehicles_listing'); ?></td>
                            <?php if (!empty($data_value['numeric']) and $data_value['numeric']): ?>
                                <td class="t-value h6"><?php echo esc_attr(ucfirst($data_meta . $affix)); ?></td>
                            <?php else: ?>
                                <?php
                                $data_meta_array = explode(',', $data_meta);
                                $datas = array();
                                if (!empty($data_meta_array)) {
                                    foreach ($data_meta_array as $data_meta_single) {
                                        $data_meta = get_term_by('slug', $data_meta_single, $data_value['slug']);
                                        if (!empty($data_meta->name)) {
                                            $datas[] = esc_attr($data_meta->name) . $affix;
                                        }
                                    }
                                }
                                ?>
                                <td class="t-value h6"><?php echo implode(', ', $datas); ?></td>
                            <?php endif; ?>
                        </tr>
                    <?php endif; ?>
                <?php endif; ?>
            <?php endforeach; ?>

            <!--VIN NUMBER-->
            <?php if (!empty($vin_num)): ?>
                <tr>
                    <td class="t-label"><?php esc_html_e('Vin', 'stm_vehicles_listing'); ?></td>
                    <td class="t-value t-vin h6"><?php echo esc_attr($vin_num); ?></td>
                </tr>
            <?php endif; ?>
        </table>
    </div>
<?php endif; ?>