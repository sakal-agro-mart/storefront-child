<?php

/**
 * 
 * 
 * removing various default actions
 */
add_action('init', 'remove_storefront_page_header');
function remove_storefront_page_header()
{
    // to remove title from all but shop page
    remove_action('storefront_page', 'storefront_page_header', 10);
    // to remove the default storefront_handheld_footer_bar
    remove_action('storefront_footer', 'storefront_handheld_footer_bar', 999);
    // to remove "Built with Storefront & WooCommerce.
    remove_action('storefront_footer', 'storefront_credit', 20);
}


/**
 * 
 * 
 * 
 * to remove title from shop page 
 */
add_filter('woocommerce_show_page_title', 'sakkal_hide_shop_page_title');
function sakkal_hide_shop_page_title($title)
{
    if (is_shop()) $title  = false;
    return $title;
}

/*
 * 
 * 
 * 
 * for overriding the storefron_handheld_footer_bar at 
 * themes/storefront/woocommerce/storefront-woocommerce-template-functions.php
 */
if (!function_exists('sakkal_storefront_handheld_footer_bar')) {
    /**
     * Display a menu intended for use on handheld devices
     *
     * @since 2.0.0
     */
    function sakkal_storefront_handheld_footer_bar()
    {
        $links = array(
            'search'     => array(
                'priority' => 20,
                'callback' => 'storefront_handheld_footer_bar_search',
            ),
            'cart'       => array(
                'priority' => 30,
                'callback' => 'storefront_handheld_footer_bar_cart_link',
            ),
        );

        if (did_action('woocommerce_blocks_enqueue_cart_block_scripts_after') || did_action('woocommerce_blocks_enqueue_checkout_block_scripts_after')) {
            return;
        }

        if (wc_get_page_id('cart') === -1) {
            unset($links['cart']);
        }

        $links = apply_filters('storefront_handheld_footer_bar_links', $links);
?>
        <div class="storefront-handheld-footer-bar">
            <ul class="columns-<?php echo count($links); ?>">
                <?php foreach ($links as $key => $link) : ?>
                    <li class="<?php echo esc_attr($key); ?>">
                        <?php
                        if ($link['callback']) {
                            call_user_func($link['callback'], $key, $link);
                        }
                        ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php
    }
}
add_action('storefront_footer', 'sakkal_storefront_handheld_footer_bar', 999);

if (!function_exists('sakkal_copyright')) {
    function sakkal_copyright()
    {
    ?>
        <div>&copy; Sakkal Agro Mart. <?php echo date('Y') ?></div>
<?php
    }
}
add_action('storefront_footer', 'sakkal_copyright', 10);
