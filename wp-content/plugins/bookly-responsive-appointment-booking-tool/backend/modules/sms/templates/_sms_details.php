<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
use Bookly\Lib\Utils\DateTime;
use Bookly\Backend\Components\Dialogs;
/** @var array $datatables */
?>
<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <button type="button" id="sms_date_range" class="btn btn-block btn-default" data-date="<?php echo date( 'Y-m-d', strtotime( '-30 days' ) ) ?> - <?php echo date( 'Y-m-d' ) ?>">
                <i class="dashicons dashicons-calendar-alt"></i>
                <input type="hidden" name="form-purchases">
                <span>
                <?php echo DateTime::formatDate( '-30 days' ) ?> - <?php echo DateTime::formatDate( 'today' ) ?>
            </span>
            </button>
        </div>
    </div>
    <div class="col-md-8 form-inline bookly-margin-bottom-lg text-right">
        <?php Dialogs\TableSettings\Dialog::renderButton( 'sms_details', 'BooklyL10n', 'details' ) ?>
    </div>
</div>

<table id="bookly-sms" class="table table-striped" style="width: 100%">
    <thead>
    <tr>
        <?php foreach ( $datatables['sms_details']['settings']['columns'] as $column => $show ) : ?>
            <?php if ( $show ) : ?>
                <th><?php echo $datatables['sms_details']['titles'][ $column ] ?></th>
            <?php endif ?>
        <?php endforeach ?>
    </tr>
    </thead>
</table>