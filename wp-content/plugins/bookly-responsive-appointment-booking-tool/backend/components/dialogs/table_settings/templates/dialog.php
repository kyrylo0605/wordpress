<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
use Bookly\Backend\Components\Controls\Buttons;
/** @var string $table */
?>
<form id="bookly-table-settings-modal" class="bookly-js-table-settings-modal modal fade" tabindex=-1 role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span>×</span></button>
                <div class="modal-title h2"><?php esc_html_e( 'Table settings', 'bookly' ) ?></div>
            </div>
            <div class="modal-body">
                <div class="panel panel-default bookly-panel-unborder">
                    <div class="panel-heading bookly-padding-horizontal-remove">
                        <div class="row">
                            <div class="col-xs-1"></div>
                            <div class="col-xs-9">
                                <div class="bookly-font-smaller bookly-color-gray"><?php esc_html_e( 'Column', 'bookly' ) ?></div>
                            </div>
                            <div class="col-xs-2">
                                <div class="bookly-font-smaller bookly-color-gray"><?php esc_html_e( 'Show', 'bookly' ) ?></div>
                            </div>
                        </div>
                    </div>
                    <ul class="bookly-margin-top-md bookly-js-table-columns"></ul>
                </div>
            </div>
            <div class="modal-footer">
                <input type="hidden" name="bookly-table-name" value="">
                <?php Buttons::renderCustom( null, 'bookly-js-table-settings-save btn-lg btn-success', esc_html__( 'Save', 'bookly' ) ) ?>
                <?php Buttons::renderCustom( null, 'bookly-js-cancel btn-lg btn-default', esc_html__( 'Close', 'bookly' ), array( 'data-dismiss' => 'modal' ) ) ?>
            </div>
        </div>
    </div>
</form>
<div id="bookly-table-settings-template" class="hidden">
    <li class="bookly-margin-bottom-md">
        <div class="row">
            <div class="col-xs-1"><i class="fa fa-fw fa-lg fa-bars text-muted bookly-cursor-move bookly-js-draghandle" title="<?php esc_attr_e( 'Reorder', 'bookly' ) ?>"></i></div>
            <div class="col-xs-9">{{title}}</div>
            <div class="col-xs-2"><input name="{{name}}" type="checkbox" {{checked}}/></div>
        </div>
    </li>
</div>