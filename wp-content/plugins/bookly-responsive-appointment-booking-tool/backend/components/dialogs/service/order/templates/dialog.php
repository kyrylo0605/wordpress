<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
use Bookly\Backend\Components\Controls\Buttons;
?>
<form id="bookly-service-order-modal" class="modal fade" tabindex=-1 role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span>×</span></button>
                <div class="modal-title h2"><?php esc_html_e( 'Services order', 'bookly' ) ?></div>
            </div>
            <div class="modal-body">
                <ul id="bookly-list"></ul>
                <p class="help-block bookly-js-without-icon"><?php esc_html_e( 'Adjust the order of services in your booking form', 'bookly' ) ?></p>
            </div>
            <div class="modal-footer">
                <?php Buttons::renderSubmit() ?>
                <?php Buttons::renderCustom( null, 'btn-lg btn-default', esc_html__( 'Close', 'bookly' ), array( 'data-dismiss' => 'modal' ) ) ?>
            </div>
        </div>
    </div>
</form>
<div class="collapse" id="bookly-service-template">
    <li class="form-group">
        <div class="row"">
            <input type="hidden" name="id" value="{{id}}"/>
            <div class="col-xs-1"><i class="fa fa-fw fa-lg fa-bars text-muted bookly-cursor-move bookly-js-draghandle" title="<?php esc_attr_e( 'Reorder', 'bookly' ) ?>"></i></div>
            <div class="col-xs-11">{{title}}</div>
        </div>
    </li>
</div>