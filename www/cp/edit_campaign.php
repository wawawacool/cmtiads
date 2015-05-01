<?php
global $current_section;
global $edited;
$current_section = 'campaigns';

require_once '../../init.php';

// Required files
require_once MAD_PATH . '/www/cp/auth.php';

require_once MAD_PATH . '/functions/adminredirect.php';

require_once MAD_PATH . '/www/cp/restricted.php';

require_once MAD_PATH . '/www/cp/admin_functions.php';

if (! check_permission ( 'inventory', $user_detail ['user_id'] )) {
	exit ();
}

global $current_action;
$current_action = 'edit';

if (isset ( $_POST ['update'] )) {
	if (do_edit ( 'campaign', $_POST, $_GET ['id'] )) {
		$edited = 1;
		MAD_Admin_Redirect::redirect ( 'edit_campaign.php?edited=1&id=' . $_GET ['id'] . '' );
	} else {
		global $edited;
		$edited = 2;
	}
}

if ($edited != 2) {
	$editdata = get_campaign_detail ( $_GET ['id'] );
	$editdata ['geo_targeting'] = $editdata ['country_target'];
	$editdata ['device_targeting'] = $editdata ['device_target'];
	$editdata ['channel_targeting'] = $editdata ['channel_target'];
	$editdata ['gender_targeting'] = $editdata ['gender_target'];
	$editdata ['income_targeting'] = $editdata ['income_target'];
	$editdata ['interest_targeting'] = $editdata ['interest_target'];
	$editdata ['location_targeting'] = $editdata ['location_target'];
	$editdata ['age_targeting'] = $editdata ['age_target'];
	$editdata ['chroniccondition_targeting'] = $editdata ['chroniccondition_target'];
	$editdata ['publication_targeting'] = $editdata ['publication_target'];
	$main_cap = get_campaign_cap_detail ( $_GET ['id'] );
	$editdata ['total_amount'] = $main_cap ['total_amount'];
	$editdata ['cap_type'] = $main_cap ['cap_type'];
	$editdata ['preload_country'] = 1;
	$editdata ['placement_select'] = load_campaign_placement_array ( $_GET ['id'] );
	$editdata ['channel_select'] = load_campaign_channel_array ( $_GET ['id'] );
	$editdata ['gender_select'] = load_campaign_gender_array ( $_GET ['id'] );
	$editdata ['income_select'] = load_campaign_income_array ( $_GET ['id'] );
	$editdata ['interest_select'] = load_campaign_interest_array ( $_GET ['id'] );
	$editdata ['location_select'] = load_campaign_location_array ( $_GET ['id'] );
	$editdata ['age_select'] = load_campaign_age_array ( $_GET ['id'] );
	$editdata ['chroniccondition_select'] = load_campaign_chroniccondition_array ( $_GET ['id'] );
	// var_dump($editdata); //verified here
	if ($editdata ['campaign_end'] == '2090-12-12') {
		$editdata ['end_date_type'] = 1;
	} else {
		$editdata ['end_date_type'] = 2;
		$end_date = explode ( '-', $editdata ['campaign_end'] );
		$end_date_array ['year'] = $end_date [0];
		$end_date_array ['day'] = $end_date [2];
		$end_date_array ['month'] = $end_date [1];
		$editdata ['enddate_value'] = '' . $end_date_array ['month'] . '/' . $end_date_array ['day'] . '/' . $end_date_array ['year'] . '';
	}
	
	$editdata ['start_date_type'] = 2;
	$start_date = explode ( '-', $editdata ['campaign_start'] );
	$start_date_array ['year'] = $start_date [0];
	$start_date_array ['day'] = $start_date [2];
	$start_date_array ['month'] = $start_date [1];
	$editdata ['startdate_value'] = '' . $start_date_array ['month'] . '/' . $start_date_array ['day'] . '/' . $start_date_array ['year'] . '';
}

require_once MAD_PATH . '/www/cp/templates/header.tpl.php';

?>

<script language="JavaScript">  

function showadiv(id) {  
//safe function to show an element with a specified id
		  
	if (document.getElementById) { // DOM3 = IE5, NS6
		document.getElementById(id).style.visibility = 'visible';
	}
	else {
		if (document.layers) { // Netscape 4
			document.id.visibility = 'visible';
		}
		else { // IE 4
			document.all.id.style.visibility = 'visible';
		}
	}
}

function hideadiv(id) {  
//safe function to hide an element with a specified id
	if (document.getElementById) { // DOM3 = IE5, NS6
		document.getElementById(id).style.visibility = 'collapse';
	}
	else {
		if (document.layers) { // Netscape 4
			document.id.visibility = 'collapse';
		}
		else { // IE 4
			document.all.id.style.visibility = 'collapse';
		}
	}

}

</script>


<div id="content">

	<div id="contentHeader">
		<h1>Edit Campaign</h1>
	</div>
	<!-- #contentHeader -->

	<div class="container">


		<div class="grid-24">
			
           <?php if ($edited==1){?>	
            <div class="box plain">
				<div class="notify notify-success">
					<h3>Successfully Updated</h3>
					<p>
						Your Campaign has successfully been updated. <a
							href="view_campaigns.php">Back to Campaign List</a>
					</p>
				</div>
				<!-- .notify -->
			</div>
            <?php } else if ($edited==2){ ?>
            <div class="box plain">
				<div class="notify notify-error">
					<h3>Error</h3>
					<p><?php echo $errormessage; ?></p>
				</div>
				<!-- .notify -->
			</div>
            <?php } ?>
            
                    
				<form method="post" id="crudcampaign" name="crudcampaign"
				class="form uniformForm">
				<input type="hidden" name="update" value="1" />				
					
				<?php
				
require_once MAD_PATH . '/www/cp/templates/forms/crud.campaign.tpl.php';
				
				?>	
                     <div class="actions">
					<button type="submit" class="btn btn-quaternary btn-large">Edit
						Campaign</button>
				</div>
				<!-- .actions -->
			</form>

		</div>
		<!-- .grid -->

		<script>
$(function () { 

	$( "#datepicker" ).datepicker();
	$( "#startdatepicker" ).datepicker({
  onSelect: function(dateText) {
	$("#startdate_value").val(dateText);
  }
});

$( "#enddatepicker" ).datepicker({
  onSelect: function(dateText) {
	$("#enddate_value").val(dateText);
  }
});

<?php

if (! empty ( $editdata ['startdate_value'] )) {
	$start_date = explode ( '/', $editdata ['startdate_value'] );
	$start_date_array ['year'] = $start_date [2];
	$start_date_array ['day'] = $start_date [1];
	$start_date_array ['month'] = $start_date [0];
	$start_date = '' . $start_date_array ['month'] . '/' . $start_date_array ['day'] . '/' . $start_date_array ['year'] . '';
	?>
$('#startdatepicker').datepicker("setDate", "<?php echo $start_date; ?>");
$("#startdate_value").val('<?php echo $start_date; ?>');
<?php } ?>

<?php

if (! empty ( $editdata ['enddate_value'] )) {
	$end_date = explode ( '/', $editdata ['enddate_value'] );
	$end_date_array ['year'] = $end_date [2];
	$end_date_array ['day'] = $end_date [1];
	$end_date_array ['month'] = $end_date [0];
	$end_date = '' . $end_date_array ['month'] . '/' . $end_date_array ['day'] . '/' . $end_date_array ['year'] . '';
	?>
$('#enddatepicker').datepicker("setDate", "<?php echo $end_date; ?>");
$("#enddate_value").val('<?php echo $end_date; ?>');
<?php } ?>
});

</script>
		<script>
<?php

if ($editdata ['publication_targeting'] == 2) {
	echo "publication_targeting('on');";
} else {
	echo "publication_targeting('off');";
}

if ($editdata ['channel_targeting'] == 2) {
	echo "channel_targeting('on');";
} else {
	echo "channel_targeting('off');";
}

if ($editdata ['gender_targeting'] == 2) {
	echo "gender_targeting('on');";
} else {
	echo "gender_targeting('off');";
}

if ($editdata ['income_targeting'] == 2) {
	echo "income_targeting('on');";
} else {
	echo "income_targeting('off');";
}

if ($editdata ['interest_targeting'] == 2) {
	echo "interest_targeting('on');";
} else {
	echo "interest_targeting('off');";
}

if ($editdata ['interest_targeting'] == 2) {
	echo "interest_targeting('on');";
} else {
	echo "interest_targeting('off');";
}

if ($editdata ['location_targeting'] == 2) {
	echo "location_targeting('on');";
} else {
	echo "location_targeting('off');";
}

if ($editdata ['age_targeting'] == 2) {
	echo "age_targeting('on');";
} else {
	echo "age_targeting('off');";
}

if ($editdata ['chroniccondition_targeting'] == 2) {
	echo "chroniccondition_targeting('on');";
} else {
	echo "chroniccondition_targeting('off');";
}

if ($editdata ['start_date_type'] == 2) {
	echo "startdate('on');";
} else {
	echo "startdate('off');";
}

if ($editdata ['end_date_type'] == 2) {
	echo "enddate('on');";
} else {
	echo "enddate('off');";
}

if ($editdata ['device_targeting'] == 2) {
	echo "device_targeting('on');";
} else {
	echo "device_targeting('off');";
}

if ($editdata ['geo_targeting'] == 2) {
	echo "geo_targeting('on');";
} else {
	echo "geo_targeting('off');";
}

if ($editdata ['campaign_type'] == 'network') {
	echo "network_campaign('on');";
} else {
	echo "network_campaign('off');";
}

?>
</script>


	</div>
	<!-- .container -->

</div>
<!-- #content -->
<?php
global $jsload;
$jsload = 1;
require_once MAD_PATH . '/www/cp/templates/footer.tpl.php';
?>