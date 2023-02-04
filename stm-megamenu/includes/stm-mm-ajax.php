<?php
function stm_mm_CurrentUrl() {
    ?>
    <script>
        var mmAjaxUrl = '<?php echo admin_url( 'admin-ajax.php', 'relative' );?>';
    </script>
    <?php
}

add_action('wp_footer', 'stm_mm_CurrentUrl');

function stm_mm_get_posts_by_cat () {
    if(empty($_GET['catId'])) die;

    $catId = intval($_GET['catId']);
    $viewStyle = $_GET['viewStyle'];
    $hasChild = $_GET['hasChild'];

    $pgp = '';
    switch ( $viewStyle ) {
        case 'stm-mm-hl':
            $pgp = ($hasChild != 'has_child') ? 7 : 4;
            break;
        case 'stm-4-col':
            $pgp = 4;
            break;
        default:
            $pgp = 3;
    }

    $query = new WP_Query( array (
        'post_type' => 'post',
        'post_status' => 'publish',
        'posts_per_page' => $pgp,
        'tax_query' => array (
            array(
                'taxonomy' => 'category',
                'field'    => 'id',
                'terms'    => $catId,
            ),
        )
    ) );

    $output = '';

    if($query->have_posts()) {
        $q=0;
        ob_start();
        while ($query->have_posts()) {
            $query->the_post();
            if($viewStyle != 'stm-mm-hl') {
                require STM_MM_DIR . 'templates/loop/loop-' . $viewStyle . '.php';
            } else {
                if($q == 0) {
                    require STM_MM_DIR . 'templates/loop/loop-' . $viewStyle . '-1.php';
                } else {
                    require STM_MM_DIR . 'templates/loop/loop-' . $viewStyle . '-2.php';
                }
            }
            $q++;
        }

        $output .= ob_get_clean();
    }
    wp_reset_postdata();

    wp_send_json($output);
    exit;
}

add_action('wp_ajax_stm_mm_get_posts_by_cat', 'stm_mm_get_posts_by_cat');
add_action('wp_ajax_nopriv_stm_mm_get_posts_by_cat', 'stm_mm_get_posts_by_cat');