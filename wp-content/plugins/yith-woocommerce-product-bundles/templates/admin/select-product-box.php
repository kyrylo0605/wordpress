<?php
// Exit if accessed directly
!defined( 'ABSPATH' ) && exit;

?>
<div class="yith-wcpb-select-product-box">
    <div class="yith-wcpb-select-product-box__filters">
        <input type="text" class="yith-wcpb-select-product-box__filter__search" placeholder="<?php _e( 'Search for a product (min 3 characters)' ) ?>"/>
    </div>
    <div class="yith-wcpb-select-product-box__products">
        <?php include YITH_WCPB_TEMPLATE_PATH . '/admin/select-product-box-products.php'; ?>
    </div>
</div>
