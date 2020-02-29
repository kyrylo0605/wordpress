<?php if ( ! defined( 'ABSPATH' ) )  exit; // Exit if accessed directly
use Bookly\Backend\Components\Dialogs;
/** @var $datatables */
?>
<div class="alert alert-info"><?php _e( 'Please take into account that not all countries by law allow custom SMS sender ID. Please check if particular country supports custom sender ID in our price list. Also please note that prices for messages with custom sender ID are usually 20% - 25% higher than normal message price.', 'bookly' ) ?></div>

<div class="row">
    <div class="col-md-11">
        <form class="form-inline bookly-margin-bottom-md">
            <div class="form-group">
                <label class="control-label" for="bookly-sender-id-input"><?php _e( 'Request Sender ID', 'bookly' ) ?></label> <?php if ( $sms->getSenderIdApprovalDate() ) : ?> <span class="bookly-vertical-middle"><?php _e( 'or', 'bookly' ) ?> <a href="#" id="bookly-reset-sender_id"><?php _e( 'Reset to default', 'bookly' ) ?></a></span><?php endif ?>
                <p class="help-block"><?php _e( 'Can only contain letters or digits (up to 11 characters).', 'bookly' ) ?></p>
                <input id="bookly-sender-id-input" class="form-control" type="text" maxlength="11" required="required" minlength="1" value="" />
                <button data-spinner-size="40" data-style="zoom-in" type="button" class="btn btn-success" id="bookly-request-sender_id"><span class="ladda-label"><?php _e( 'Request', 'bookly' ) ?></span><span class="ladda-spinner"></span></button>
                <button data-spinner-size="40" data-style="zoom-in" type="button" class="btn btn-danger" id="bookly-cancel-sender_id" style="display:none"><span class="ladda-label"><?php _e( 'Cancel request', 'bookly' ) ?></span><span class="ladda-spinner"></span></button>
            </div>
        </form>
    </div>
    <div class="col-md-1 form-inline bookly-margin-bottom-lg text-right">
        <?php Dialogs\TableSettings\Dialog::renderButton( 'sms_sender', 'BooklyL10n', 'sender' ) ?>
    </div>
</div>

<table id="bookly-sender-ids" class="table table-striped" style="width: 100%">
    <thead>
    <tr>
        <?php foreach ( $datatables['sms_sender']['settings']['columns'] as $column => $show ) : ?>
            <?php if ( $show ) : ?>
                <th><?php echo $datatables['sms_sender']['titles'][ $column ] ?></th>
            <?php endif ?>
        <?php endforeach ?>
    </tr>
    </thead>
</table>