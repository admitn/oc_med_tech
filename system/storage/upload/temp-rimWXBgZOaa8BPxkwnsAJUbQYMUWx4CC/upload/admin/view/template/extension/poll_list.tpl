<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <style>
	.getPollVotes { display: block; }
	.panel-body .btn { margin-left: 10px; }
  </style>
  <div class="page-header">
    <div class="container-fluid">
	  <div class="pull-right">
		<a href="<?php echo $add; ?>" data-toggle="tooltip" question="<?php echo $button_add; ?>" title="<?php echo $button_add; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></a>
        <button type="button" data-toggle="tooltip" question="<?php echo $button_copy; ?>" title="<?php echo $button_copy; ?>" class="btn btn-warning" onclick="$('#form-module').attr('action', '<?php echo $copy; ?>'); $('#form-module').submit(); return false;"><i class="fa fa-copy"></i></button>
        <button type="button" data-toggle="tooltip" question="<?php echo $button_delete; ?>" title="<?php echo $button_delete; ?>" class="btn btn-danger" onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form-module').submit() : false;"><i class="fa fa-trash-o"></i></button>
      </div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-question"><i class="fa fa-bars"></i> <?php echo $text_list; ?></h3>
	  </div>
	  
      <div class="panel-body">
	  
          <div class="well">
          <div class="row">
            <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-name"><?php echo $entry_name; ?></label>
                <input type="text" name="filter_name" value="<?php echo $filter_name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
              </div>
              </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-date-added"><?php echo $entry_date_added; ?></label>
                <div class="input-group date">
                  <input type="text" name="filter_date_added" value="<?php echo $filter_date_added; ?>" placeholder="<?php echo $entry_date_added; ?>" data-date-format="YYYY-MM-DD" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
			  <button type="button" id="button-view-all" class="btn btn-primary pull-right"><?php echo $button_view_all_poll; ?></button>
              <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
              
               
            </div>
          </div>
        </div>
      
      
      
        <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-module" class="form-horizontal">
          <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
			  <thead>
				<tr>
				  <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
				  <td class="text-left"><?php echo $text_title; ?></td>
				   <td class="text-center"><?php echo $entry_date_start; ?></td>
				   <td class="text-center"><?php echo $entry_date_end; ?></td>
				   <td class="text-center"><?php echo $text_sort_order; ?></td>
				   <td class="text-center"><?php echo $text_votes; ?></td>
				   <td class="text-center"><?php echo $text_status; ?></td>
				  <td class="text-right"><?php echo $text_action; ?></td>
				</tr>
			  </thead>
			  <tbody>
				<?php if ($all_poll) { ?>
				  <?php foreach ($all_poll as $poll) { ?>
				  <tr>
					<td width="1" style="text-align: center;"><input type="checkbox" name="selected[]" value="<?php echo $poll['poll_id']; ?>" /></td>
					<td class="text-left"><?php echo $poll['name']; ?></td>
					<td class="text-center"><?php echo $poll['date_added']; ?></td>
					<td class="text-center"><?php echo $poll['date_end']; ?></td>
					<td class="text-center"><?php echo $poll['sort_order']; ?></td>
					<td class="text-center"><a href="<?php echo $votes .'&poll_id='.$poll['poll_id'];?>" class="getPollVotes"><?php echo $poll['votes']; ?></a></td>
					<td class="text-center"><?php echo $poll['status']; ?></td>
					<td class="text-right"><a href="<?php echo $poll['edit']; ?>"><?php echo $text_edit; ?></a></td>
				  </tr>
				  <?php } ?>
				<?php } else { ?>
				  <tr>
					<td colspan="8" class="text-center"><?php echo $text_no_results; ?></td>
				  </tr>
				<?php } ?>
			  </tbody>
			  <tfoot>
				<tr>
					<td colspan="8" class="text-right">
						<a href="<?php echo $add; ?>" data-toggle="tooltip" question="<?php echo $button_add; ?>" class="btn btn-primary"><i class="fa fa-plus"></i> <?php echo $button_add; ?></a>
					</td>
				</tr>
			  </tfoot>
			</table>
          </div>
        </form>
		<div class="row">
          <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
          <div class="col-sm-6 text-right"><?php echo $results; ?></div>
        </div>
		
      </div>
	  
	<div style="text-align:center; color:#222222;">Poll System V 1.1</div>
	
  </div>
</div>
 <script type="text/javascript"><!--
$('#button-filter').on('click', function() {
	url = 'index.php?route=extension/poll&token=<?php echo $token; ?>';
	
	var filter_name = $('input[name=\'filter_name\']').val();
	
	if (filter_name) {
		url += '&filter_name=' + encodeURIComponent(filter_name);
	}
	
		
	var filter_date_added = $('input[name=\'filter_date_added\']').val();
	
	if (filter_date_added) {
		url += '&filter_date_added=' + encodeURIComponent(filter_date_added);
	}
	
	location = url;
});

$('#button-view-all').on('click', function() {
	url = 'index.php?route=extension/poll&token=<?php echo $token; ?>';	
	location = url;
});

$(document).ready(function() {
	$('.getPollVotes').magnificPopup({
		tLoading: 'Loading frame #%curr%...',
		type: 'iframe', 
		closeOnBgClick: true,
		iframe: {
			markup: '<style>.mfp-iframe-holder .mfp-content {max-width: 600px; height:450px;} .mfp-iframe-scaler iframe {background:#FFFFFF;}</style>'+
					'<div class="mfp-iframe-scaler myIframe">'+
					'<div class="mfp-close"></div>'+
					'<div id="page-preloader"><span class="spinner"></span></div>'+
					'<iframe class="mfp-iframe" frameborder="0" scrolling="auto" allowfullscreen></iframe>'+
					'</div>', 
		},
		callbacks: { close: function() { /* location.reload(true); */ } }	});
});
//--></script> 

<script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false
});
//--></script>

<?php echo $footer; ?>