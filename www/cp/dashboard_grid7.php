<div class="grid-7">
				
				
				<?php if (get_setup_percentage()<100){ ?>
				<div id="gettingStarted" class="box">
					<h3> Getting Started</h3>

					<p>We'll help you get up and running in no time!</p>

					<div class="progress-bar secondary">
						<div class="bar" style="width: <?php echo get_setup_percentage(); ?>%;"><?php echo get_setup_percentage(); ?>%</div>
					</div>

					<ul class="bullet secondary">
                    	<li><a <?php if (check_cron_active()){?>style="text-decoration:line-through;"<?php } ?> target="_blank" href="http://help.madserve.org/cron.php">Set Up Daily Cron Job</a></li>
						<li><a <?php if (check_setup('networks')){?>style="text-decoration:line-through;"<?php } ?> href="ad_networks.php">Setup Ad Networks</a></li>
                        <li><a <?php if (check_setup('publication')){?>style="text-decoration:line-through;"<?php } ?> href="add_publication.php">Add a Publication</a></li>
						<li><a <?php if (check_setup('campaign')){?>style="text-decoration:line-through;"<?php } ?> href="create_campaign.php">Add a Campaign</a></li>
					</ul>
				</div>
                <?php } ?>
				
                <?php if (getconfig_var('update_check')==1){
					$box_url='http://network.madserve.org/newsbox.php?dv='.urlencode(getconfig_var('db_install_version')).'&cv='.urlencode(MAD_VERSION).''; if (getconfig_var('allow_statistical_info')==1){
					$data_yesterday=get_reporting_data("publisher", date('d', mktime(0, 0, 0, date("m") , date("d")-1 , date("Y"))), date('m', mktime(0, 0, 0, date("m") , date("d")-1 , date("Y"))), date('Y', mktime(0, 0, 0, date("m") , date("d")-1 , date("Y"))), '');
					$box_url=$box_url . '&u='.urlencode(getconfig_var('installation_id')).'&d='.urlencode(date('dmy', time()-86400)).'&re='.urlencode($data_yesterday['total_requests']).'&im='.urlencode($data_yesterday['total_impressions']).'&cl='.urlencode($data_yesterday['total_clicks']).'';} ?>
			  <div class="box">
                <iframe width="100%" height="350" allowtransparency="1" scrolling="no" frameborder="0" src="<?php echo $box_url; ?>"></iframe>
				<!--<ul class="bullet secondary">
						<li><a href="<?php echo "../../sdk/ios_latest.zip"; ?>">mAdserve iOS SDK</a></li>
						<li><a href="<?php echo "../../sdk/android_latest.zip"; ?>">mAdserve Android SDK</a></li>
						<li>Campaign: <a href="#">Motorola Razr</a></li>
						<li><a href="#">Server Configuration</a></li>
				</ul> -->
</div> 
<?php } ?>

		<a href="../../<?php echo MAD_IOS_SDK_LOCATION; ?>" class="btn btn-primary btn-large dashboard_add">Download iOS SDK</a>
					<a href="../../<?php echo MAD_ANDROID_SDK_LOCATION; ?>" class="btn btn-quaternary btn-large dashboard_add">Download Android SDK</a>
				<!-- .box -->
				
				
				<!--<div class="box plain">
					
					<a href="javascript:;" class="btn btn-primary btn-large dashboard_add">Add A Session</a>
					<a href="javascript:;" class="btn btn-tertiary btn-large dashboard_add">Add A Client</a>
					<a href="javascript:;" class="btn btn-quaternary btn-large dashboard_add">Send Invoices</a>
					
				</div> -->
				
				
				
				<!--<div class="box">
					<h3>Progress Bars</h3>
					
					<div class="progress-bar primary">
						<div class="bar" style="width: 65%;">65%</div>
					</div>
					
					<div class="progress-bar secondary">
						<div class="bar" style="width: 42%;">42%</div>
					</div>
					
					<div class="progress-bar tertiary thin">
						<div class="bar" style="width: 83%;">83%</div>
					</div>
					
					<div class="progress-bar quaternary thin">
						<div class="bar" style="width: 93%;">93%</div>
					</div>
				</div> -->
				
				
				
				
			</div> <!-- .grid -->
			