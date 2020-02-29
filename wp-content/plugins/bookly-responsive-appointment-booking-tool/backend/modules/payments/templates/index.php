<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
use Bookly\Lib\Entities\Payment;
use Bookly\Backend\Components;
use Bookly\Backend\Modules\Payments\Proxy;
/** @var array $datatables */
?>
<div id="bookly-tbs" class="wrap">
    <div class="bookly-tbs-body">
        <div class="page-header text-right clearfix">
            <div class="bookly-page-title">
                <?php _e( 'Payments', 'bookly' ) ?>
            </div>
            <?php Components\Support\Buttons::render( $self::pageSlug() ) ?>
        </div>
        <div class="panel panel-default bookly-main">
            <div class="panel-body">
                <?php Components\Dialogs\TableSettings\Dialog::renderButton( 'payments' ) ?>
                <div class="row">
                    <div class="col-md-1 col-lg-1">
                        <div class="form-group">
                            <input class="form-control" type="text" id="bookly-filter-id" placeholder="<?php esc_attr_e( 'No.', 'bookly' ) ?>" />
                        </div>
                    </div>
                    <div class="col-md-4 col-lg-3">
                        <div class="bookly-margin-bottom-lg bookly-relative">
                            <button type="button" class="btn btn-block btn-default" id="bookly-filter-date" data-date="<?php echo date( 'Y-m-d', strtotime( '-30 day' ) ) ?> - <?php echo date( 'Y-m-d' ) ?>">
                                <i class="dashicons dashicons-calendar-alt"></i>
                                <span>
                                    <?php echo Bookly\Lib\Utils\DateTime::formatDate( '-30 days' ) ?> - <?php echo Bookly\Lib\Utils\DateTime::formatDate( 'today' ) ?>
                                </span>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-1 col-lg-1">
                        <div class="form-group">
                            <select id="bookly-filter-type" class="form-control bookly-js-select" data-placeholder="<?php esc_attr_e( 'Type', 'bookly' ) ?>">
                                <?php foreach ( $types as $type ) : ?>
                                    <option value="<?php echo esc_attr( $type ) ?>">
                                        <?php echo Payment::typeToString( $type ) ?>
                                    </option>
                                <?php endforeach ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-1 col-lg-2">
                        <div class="form-group">
                            <select class="form-control <?php echo $customers === false ? 'bookly-js-select-ajax' : 'bookly-js-select' ?>" id="bookly-filter-customer" data-placeholder="<?php esc_attr_e( 'Customer', 'bookly' ) ?>" <?php echo $customers === false ? 'data-ajax--action' : 'data-action' ?>="bookly_get_customers_list">
                                <?php if ( $customers !== false ) : ?>
                                    <?php foreach ( $customers as $customer_id => $customer ) : ?>
                                        <option value="<?php echo $customer_id ?>" data-search='<?php echo json_encode( array_values( $customer ) ) ?>'><?php echo esc_html( $customer['full_name'] ) ?></option>
                                    <?php endforeach ?>
                                <?php endif ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-1 col-lg-2">
                        <div class="form-group">
                            <select id="bookly-filter-staff" class="form-control bookly-js-select" data-placeholder="<?php esc_attr_e( 'Provider', 'bookly' ) ?>">
                                <?php foreach ( $providers as $provider ) : ?>
                                    <option value="<?php echo $provider['id'] ?>"><?php echo esc_html( $provider['full_name'] ) ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-1 col-lg-2">
                        <div class="form-group">
                            <select id="bookly-filter-service" class="form-control bookly-js-select" data-placeholder="<?php esc_attr_e( 'Service', 'bookly' ) ?>">
                                <?php foreach ( $services as $service ) : ?>
                                    <option value="<?php echo $service['id'] ?>"><?php echo esc_html( $service['title'] ) ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-1 col-lg-1">
                        <div class="form-group">
                            <select id="bookly-filter-status" class="form-control bookly-js-select" data-placeholder="<?php esc_attr_e( 'Status', 'bookly' ) ?>">
                                <option value="<?php echo Payment::STATUS_COMPLETED ?>"><?php echo Payment::statusToString( Payment::STATUS_COMPLETED ) ?></option>
                                <option value="<?php echo Payment::STATUS_PENDING ?>"><?php echo Payment::statusToString( Payment::STATUS_PENDING ) ?></option>
                                <option value="<?php echo Payment::STATUS_REJECTED ?>"><?php echo Payment::statusToString( Payment::STATUS_REJECTED ) ?></option>
                            </select>
                        </div>
                    </div>
                </div>

                <table id="bookly-payments-list" class="table table-striped" width="100%">
                    <thead>
                    <tr>
                        <?php foreach ( $datatables['payments']['settings']['columns'] as $column => $show ) : ?>
                            <?php if ( $show ) : ?>
                            <th><?php echo $datatables['payments']['titles'][ $column ] ?></th>
                            <?php endif ?>
                        <?php endforeach ?>
                        <th></th>
                        <th width="16"><input type="checkbox" id="bookly-check-all"/></th>
                    </tr>
                    </thead>
                    <?php if ( key_exists( 'paid', $datatables['payments']['settings']['columns'] ) && $datatables['payments']['settings']['columns']['paid'] ) : ?>
                        <tfoot>
                        <tr>
                            <?php $columns = array_filter( $datatables['payments']['settings']['columns'] ) ?>
                            <?php $index = array_search( 'paid', array_keys( $columns ) ) ?>
                            <?php for ( $column = 0; $column < count( $columns ) + 2; $column ++ ) : ?>
                                <?php if ( $column == $index - 1 ) : ?>
                                    <th>
                                        <div class="pull-right"><?php _e( 'Total', 'bookly' ) ?>:</div>
                                    </th>
                                <?php elseif ( $column == $index ) : ?>
                                    <th><span id="bookly-payment-total"></span></th>
                                <?php else : ?>
                                    <th></th>
                                <?php endif ?>
                            <?php endfor ?>
                        </tr>
                        </tfoot>
                    <?php endif ?>
                </table>
                <div class="text-right bookly-margin-top-lg">
                    <?php Proxy\Invoices::renderDownloadButton() ?>
                    <?php Components\Controls\Buttons::renderDelete() ?>
                </div>
            </div>
        </div>

        <div ng-app="paymentDetails" ng-controller="paymentDetailsCtrl">
            <div payment-details-dialog></div>
            <?php Components\Dialogs\Payment\Dialog::render() ?>
        </div>
        <?php Components\Dialogs\TableSettings\Dialog::render() ?>
    </div>
</div>