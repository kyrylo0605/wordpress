<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
use Bookly\Backend\Components\Controls\Buttons;
use Bookly\Backend\Components\Support\Lib\Urls;
use Bookly\Lib\Utils\Common;
?>
<div id="bookly-tbs" class="wrap bookly-js-nps-notice">
    <div id="bookly-nps-notice" class="alert alert-info">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <div class="form-row">
            <div class="mr-3"><i class="fas fa-info-circle fa-2x"></i></div>
            <div class="col">
                <div id="bookly-nps-quiz" class="my-2">
                    <label><?php esc_html_e( 'How likely is it that you would recommend Bookly to a friend or colleague?', 'bookly' ) ?></label>
                    <div>
                        <?php for ( $i = 1; $i <= 10; ++ $i ): ?><i class="bookly-js-star far fa-star fa-lg text-muted"></i><?php endfor ?>
                    </div>
                </div>
                <div id="bookly-nps-form" class="mt-4 collapse" style="max-width:400px;">
                    <div class="form-group">
                        <label for="bookly-nps-msg" class="control-label"><?php esc_html_e( 'What do you think should be improved?', 'bookly' ) ?></label>
                        <textarea id="bookly-nps-msg" class="form-control"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="bookly-nps-email" class="control-label"><?php esc_html_e( 'Please enter your email (optional)', 'bookly' ) ?></label>
                        <input type="text" id="bookly-nps-email" class="form-control" value="<?php echo esc_attr( $current_user->user_email ) ?>"/>
                    </div>
                    <?php Buttons::render( 'bookly-nps-btn', 'btn-success', __( 'Send', 'bookly' ) ) ?>
                </div>
                <div id="bookly-nps-thanks" class="collapse mt-1">
                    <?php printf(
                        __( 'Please leave your feedback <a href="%s" target="_blank">here</a>.', 'bookly' ),
                        Common::prepareUrlReferrers( Urls::REVIEWS_PAGE, 'nps' )
                    ) ?>
                </div>
            </div>
        </div>
    </div>
</div>