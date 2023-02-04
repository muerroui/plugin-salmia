<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
?>

<div class="stm_ajax_pagination">
    <?php
    echo paginate_links(array(
        'type' => 'list',
        'prev_text' => '<i class="fas fa-angle-left"></i>',
        'next_text' => '<i class="fas fa-angle-right"></i>',
    ));
    ?>
</div>