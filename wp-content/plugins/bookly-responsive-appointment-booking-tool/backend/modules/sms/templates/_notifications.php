<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
use Bookly\Backend\Components\Controls\Buttons;
use Bookly\Lib\Utils\Common;
use Bookly\Backend\Components\Controls\Inputs;
use Bookly\Backend\Components\Dialogs;
/** @var array $datatables */
?>
<input type="hidden" name="form-notifications">
<div class="form-inline bookly-margin-bottom-xlg">
    <div class="form-group">
        <label for="admin_phone">
            <?php esc_html_e( 'Administrator phone', 'bookly' ) ?>
        </label>
        <p class="help-block"><?php esc_html_e( 'Enter a phone number in international format. E.g. for the United States a valid phone number would be +17327572923.', 'bookly' ) ?></p>
        <div>
            <input class="form-control" id="admin_phone" name="bookly_sms_administrator_phone" type="text" value="<?php form_option( 'bookly_sms_administrator_phone' ) ?>">


            <div class="btn-group">
                <button class="btn btn-success" id="send_test_sms"><?php esc_html_e( 'Send test SMS', 'bookly' ) ?></button>
                <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="caret"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                </button>
                <ul class="dropdown-menu">
                    <li><a href="#" data-action="save-administrator-phone"><?php esc_html_e( 'Save administrator phone', 'bookly' ) ?></a></li>
                </ul>
            </div>

        </div>
    </div>
</div>

<form method="post" action="<?php echo Common::escAdminUrl( $self::pageSlug() ) ?>">
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <input class="form-control" type="text" id="bookly-filter" placeholder="<?php esc_attr_e( 'Quick search notifications', 'bookly' ) ?>"/>
            </div>
        </div>
        <div class="col-md-8 form-inline bookly-margin-bottom-lg text-right">
            <?php Dialogs\Sms\Dialog::renderNewNotificationButton() ?>
            <?php Dialogs\TableSettings\Dialog::renderButton( 'sms_notifications', 'BooklyL10n' ) ?>
        </div>
    </div>

    <table id="bookly-js-notification-list" class="table table-striped" style="width: 100%">
        <thead>
        <tr>
            <?php foreach ( $datatables['sms_notifications']['settings']['columns'] as $column => $show ) : ?>
                <?php if ( $show ) : ?>
                    <?php if ( $column  === 'type' ) : ?>
                        <th width="1"></th>
                    <?php else : ?>
                        <th><?php echo $datatables['sms_notifications']['titles'][ $column ] ?></th>
                    <?php endif ?>
                <?php endif ?>
            <?php endforeach ?>
            <th width="75"></th>
            <th width="16"><input type="checkbox" class="bookly-js-check-all"/></th>
        </tr>
        </thead>
    </table>

    <div class="form-inline bookly-margin-bottom-lg text-right">
        <?php Inputs::renderCsrf() ?>
        <?php Buttons::renderCustom( 'bookly-js-delete-notifications', 'btn-danger', esc_html__( 'Delete', 'bookly' ) . '...' ) ?>
    </div>

    <div class="alert alert-info">
        <div class="row">
            <div class="col-md-12">
                <?php if ( is_multisite() ) : ?>
                    <p><?php printf( __( 'To send scheduled notifications please refer to <a href="%1$s">Bookly Multisite</a> add-on <a href="%2$s">message</a>.', 'bookly' ), Common::prepareUrlReferrers( 'http://codecanyon.net/item/bookly-multisite-addon/13903524?ref=ladela', 'cron_setup' ), network_admin_url( 'admin.php?page=bookly-multisite-network' ) ) ?></p>
                <?php else : ?>
                    <p><?php esc_html_e( 'To send scheduled notifications please execute the following command hourly with your cron:', 'bookly' ) ?></p>
                    <code class="bookly-text-wrap">wget -q -O - <?php echo site_url( 'wp-cron.php' ) ?></code>
                <?php endif ?>
            </div>
        </div>
    </div>
</form>
<?php Dialogs\Sms\Dialog::render() ?>