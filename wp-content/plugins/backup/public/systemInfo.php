<?php

require_once(dirname(__FILE__).'/boot.php');
require_once(SG_PUBLIC_INCLUDE_PATH.'header.php');
require_once(SG_PUBLIC_INCLUDE_PATH.'sidebar.php');
require_once(SG_SCHEDULE_PATH.'SGSchedule.php');

?>

<div id="sg-content-wrapper">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
				<form class="form-horizontal" method="post" data-sgform="ajax" data-type="sgsettings">
					<fieldset>
						<legend><?php _backupGuardT('System information')?><?php echo backupGuardLoggedMessage(); ?></legend>
						<div class="form-group">
							<label class="col-md-8 sg-control-label sg-user-info">
								<?php _backupGuardT('Disk free space'); ?>
							</label>
							<div class="col-md-3 pull-right text-right">
								<label class="sg-control-label">
									<?php echo convertToReadableSize(@disk_free_space(SG_APP_ROOT_DIRECTORY)); ?>
								</label>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-8 sg-control-label sg-user-info">
								<?php _backupGuardT('Memory limit'); ?>
							</label>
							<div class="col-md-3 pull-right text-right">
								<label class="sg-control-label">
									<?php echo SGBoot::$memoryLimit; ?>
								</label>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-8 sg-control-label sg-user-info">
								<?php _backupGuardT('Max execution time'); ?>
							</label>
							<div class="col-md-3 pull-right text-right">
								<label class="sg-control-label">
									<?php echo SGBoot::$executionTimeLimit; ?>
								</label>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-8 sg-control-label sg-user-info">
								<?php _backupGuardT('PHP version'); ?>
							</label>
							<div class="col-md-3 pull-right text-right">
								<label class="sg-control-label">
									<?php echo PHP_VERSION; ?>
								</label>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-8 sg-control-label sg-user-info">
								<?php _backupGuardT('MySQL version'); ?>
							</label>
							<div class="col-md-3 pull-right text-right">
								<label class="sg-control-label">
									<?php echo SG_MYSQL_VERSION; ?>
								</label>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-8 sg-control-label sg-user-info">
								<?php _backupGuardT('Int size'); ?>
							</label>
							<div class="col-md-3 pull-right text-right">
								<?php echo '<label>'.PHP_INT_SIZE.'</label>'; ?>
								<?php
									if (PHP_INT_SIZE < 8) {
										echo '<label class="sg-control-label backup-guard-label-warning">Notice that archive size cannot be bigger than 2GB. This limitaion is comming from system.</label>';
									}
								?>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-8 sg-control-label sg-user-info">
								<?php _backupGuardT('Curl version'); ?>
							</label>
							<div class="col-md-3 pull-right text-right">
								<?php
									if (function_exists('curl_version') && function_exists('curl_exec')) {
										$cv = curl_version();
										echo '<label class="">'.$cv['version'].' / SSL: '.$cv['ssl_version'].' / libz: '.$cv['libz_version'].'</label>';
									}
									else {
										echo '<label class="sg-control-label backup-guard-label-warning">Curl required for BackupGuard for better functioning.</label>';
									}
								?>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-8 sg-control-label sg-user-info">
								<?php _backupGuardT('Is cron available'); ?>
							</label>
							<div class="col-md-3 pull-right text-right">
								<?php
									$isCronAvailable = SGSchedule::isCronAvailable();
									if ($isCronAvailable) {
										echo '<label class="sg-control-label backup-guard-label-success">Yes</label>';
									}
									else {
										echo '<label class="sg-control-label backup-guard-label-warning">Please consider enabling WP Cron in order to be able to setup schedules.</label>';
									}
								?>
							</div>
						</div>
					</fieldset>
				</form>
			</div>
		</div>
	</div>
	<?php require_once(SG_PUBLIC_INCLUDE_PATH . '/footer.php'); ?>
</div>
