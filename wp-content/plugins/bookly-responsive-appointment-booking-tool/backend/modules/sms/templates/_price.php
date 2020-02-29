<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
use Bookly\Backend\Components\Dialogs;
/** @var array $datatables */
?>
<div class="row">
    <div class="col-xs-12 text-right">
        <?php Dialogs\TableSettings\Dialog::renderButton( 'sms_prices', 'BooklyL10n', 'prices' ) ?>
    </div>
</div>
<div class="intl-tel-input">
    <table id="bookly-prices" class="table table-striped" width="100%">
        <thead>
        <tr>
            <?php foreach ( $datatables['sms_prices']['settings']['columns'] as $column => $show ) : ?>
                <?php if ( $show ) : ?>
                    <th><?php echo $datatables['sms_prices']['titles'][ $column ] ?></th>
                <?php endif ?>
            <?php endforeach ?>
        </tr>
        </thead>
    </table>
</div>
<p><?php _e( 'If you do not see your country in the list please contact us at <a href="mailto:support@bookly.info">support@bookly.info</a>.', 'bookly' ) ?></p>