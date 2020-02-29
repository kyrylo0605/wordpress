<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
use Bookly\Backend\Components;
use Bookly\Lib;
/** @var array $datatables */
?>
<div id="bookly-tbs" class="wrap">
    <div class="bookly-tbs-body">
        <div class="page-header text-right clearfix">
            <?php if ( Lib\Utils\Common::isCurrentUserAdmin() ) : ?>
                <div class="bookly-page-title">
                    <?php esc_html_e( 'Staff Members', 'bookly' ) ?>
                    <small class="bookly-color-gray">(<small class="bookly-js-staff-count"><div class="bookly-loading-16"></div></small>)
                    </small>
                </div>
            <?php else : ?>
                <div class="bookly-page-title">
                    <?php esc_html_e( 'Profile', 'bookly' ) ?>
                    <small class="bookly-js-staff-count" style="color: transparent"></small>
                </div>
            <?php endif ?>
            <?php Components\Support\Buttons::render( $self::pageSlug() ) ?>
        </div>
        <div class="panel panel-default bookly-main">
            <div class="panel-body">
                <?php if ( Lib\Utils\Common::isCurrentUserAdmin() ): ?>
                    <div class="row">
                        <div class="col-md-12 form-inline bookly-margin-bottom-lg text-right">
                            <div class="form-group">
                                <?php Components\Controls\Buttons::renderModalActivator( 'bookly-staff-order-modal', 'btn-default bookly-btn-block-xs', esc_html__( 'Staff members order', 'bookly' ) ) ?>
                            </div>
                            <?php Components\Dialogs\Staff\Categories\Proxy\Pro::renderAdd() ?>
                            <div class="form-group">
                                <?php Components\Controls\Buttons::renderModalActivator( 'bookly-create-staff-modal', 'btn-success bookly-btn-block-xs', esc_html__( 'Add staff', 'bookly' ), array(), '<i class="fa fa-fw fa-plus"></i> {caption}' ) ?>
                            </div>
                            <?php Components\Dialogs\TableSettings\Dialog::renderButton( 'staff_members' ) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <input class="form-control" type="text" id="bookly-filter-search" placeholder="<?php esc_attr_e( 'Quick search staff', 'bookly' ) ?>"/>
                            </div>
                        </div>
                        <div class="col-md-3 col-lg-2">
                            <div class="form-group">
                                <select class="form-control bookly-js-select" id="bookly-filter-category" data-placeholder="<?php esc_attr_e( 'Categories', 'bookly' ) ?>">
                                    <?php foreach ( $categories as $category ) : ?>
                                        <option value="<?php echo $category['id'] ?>"><?php echo esc_html( $category['name'] ) ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <select class="form-control bookly-js-select" id="bookly-filter-visibility" data-placeholder="<?php esc_attr_e( 'Visibility', 'bookly' ) ?>">
                                    <option value="public"><?php esc_html_e( 'Public', 'bookly' ) ?></option>
                                    <option value="private"><?php esc_html_e( 'Private', 'bookly' ) ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <?php if ( Lib\Config::proActive() ): ?>
                            <div class="form-group">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" id="bookly-filter-archived"/>
                                        <?php esc_html_e( 'Show archived', 'bookly' ) ?>
                                    </label>
                                </div>
                            </div>
                            <?php endif ?>
                        </div>
                    </div>

                <?php endif ?>

                <table id="bookly-staff-list" class="table table-striped" width="100%">
                    <thead>
                    <tr>
                        <?php foreach ( $datatables['staff_members']['settings']['columns'] as $column => $show ) : ?>
                            <?php if ( $show ) : ?>
                                <th><?php echo $datatables['staff_members']['titles'][ $column ] ?></th>
                            <?php endif ?>
                        <?php endforeach ?>
                        <th width="75"></th>
                        <th width="16"><input type="checkbox" id="bookly-check-all"/></th>
                    </tr>
                    </thead>
                </table>

                <div class="text-right bookly-margin-top-lg">
                    <?php if ( Lib\Utils\Common::isCurrentUserAdmin() ): ?>
                        <?php Components\Controls\Buttons::renderDelete() ?>
                    <?php endif ?>
                </div>
            </div>
        </div>
    </div>

    <?php Components\Dialogs\Common\CascadeDelete::render() ?>
    <?php Components\Dialogs\Common\UnsavedChanges::render() ?>
    <?php Components\Dialogs\Staff\Categories\Proxy\Pro::renderDialog() ?>
    <?php Components\Dialogs\Staff\Edit\Dialog::render() ?>
    <?php Components\Dialogs\Staff\Order\Dialog::render() ?>
    <?php Components\Dialogs\TableSettings\Dialog::render() ?>
</div>