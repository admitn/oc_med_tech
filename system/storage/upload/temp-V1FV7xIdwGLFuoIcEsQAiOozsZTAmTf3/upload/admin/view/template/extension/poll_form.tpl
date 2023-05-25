<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
	<style>
	@font-face {
	  font-family: 'Glyphicons Halflings';
	  src: url('view/javascript/bootstrap/fonts/glyphicons-halflings-regular.eot');
	  src: url('view/javascript/bootstrap/fonts/glyphicons-halflings-regular.eot?#iefix') format('embedded-opentype'), url('view/javascript/bootstrap/fonts/glyphicons-halflings-regular.woff') format('woff'), url('view/javascript/bootstrap/fonts/glyphicons-halflings-regular.ttf') format('truetype'), url('view/javascript/bootstrap/fonts/glyphicons-halflings-regular.svg#glyphicons-halflingsregular') format('svg');
	}
	</style>
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-poll" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
		<?php if($votes && !empty($votes_url)) { ?>
			<a href="<?php echo $votes_url; ?>" data-toggle="tooltip" title="<?php echo $button_votes; ?>"  class="getPollVotes btn btn-info"><i class="fa fa-eye"></i></a>
		<?php } ?>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
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
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-poll"><i class="fa fa-pencil"></i> <?php echo $heading_title; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-poll" class="form-horizontal">
          <ul class="nav nav-tabs" id="language" style="margin-bottom:0px;">
			<?php foreach ($languages as $language) { ?>
			<li><a href="#language<?php echo $language['language_id']; ?>" data-toggle="tab"><img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" poll="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a></li>
			<?php } ?>
		  </ul>
		  <div class="tab-content" style="border: 1px solid #DDD; border-top: none; padding: 15px;">
			<?php foreach ($languages as $language) { ?>
			<div class="tab-pane" id="language<?php echo $language['language_id']; ?>">
			  <div class="form-group required">
				<label class="col-sm-2 control-label" for="input-poll<?php echo $language['language_id']; ?>"><?php echo $text_name; ?></label>
				<div class="col-sm-10">
				  <input type="text" name="poll[<?php echo $language['language_id']; ?>][name]" value="<?php echo isset($poll[$language['language_id']]) ? $poll[$language['language_id']]['name'] : ''; ?>" placeholder="<?php echo $text_name; ?>" id="input-poll<?php echo $language['language_id']; ?>" class="form-control" <?php //echo ($votes) ? 'readonly="readonly"' : ''; ?> />
				  <?php if (isset($error_name[$language['language_id']])) { ?>
				  <div class="text-danger"><?php echo $error_name[$language['language_id']]; ?></div>
				  <?php } ?>
				  
				</div>
			  </div>
			  
			  <div class="form-group">
				<label class="col-sm-2 control-label" for="input-small-description<?php echo $language['language_id']; ?>"><?php echo $text_small_description; ?></label>
				<div class="col-sm-10">
				  <textarea name="poll[<?php echo $language['language_id']; ?>][small_description]" placeholder="<?php echo $text_small_description; ?>" id="input-small-description<?php echo $language['language_id']; ?>" class="form-control" rows="2" ><?php echo isset($poll[$language['language_id']]) ? $poll[$language['language_id']]['small_description'] : ''; ?></textarea>
				</div>
			  </div>
			  
			  <div class="form-group">
				<label class="col-sm-2 control-label" for="input-description<?php echo $language['language_id']; ?>"><?php echo $text_description; ?></label>
				<div class="col-sm-10">
				  <textarea name="poll[<?php echo $language['language_id']; ?>][description]" placeholder="<?php echo $text_description; ?>" id="input-description<?php echo $language['language_id']; ?>" <?php //echo ($votes) ? 'readonly="readonly"' : ''; ?> class="form-control summernote" rows="4" ><?php echo isset($poll[$language['language_id']]) ? $poll[$language['language_id']]['description'] : ''; ?></textarea>
				</div>
			  </div>
			  
			  <div class="form-group">
				<label class="col-sm-2 control-label" for="input-meta-title<?php echo $language['language_id']; ?>"><?php echo $text_meta_title; ?></label>
				<div class="col-sm-10">
				  <input type="text" name="poll[<?php echo $language['language_id']; ?>][meta_title]" value="<?php echo isset($poll[$language['language_id']]) ? $poll[$language['language_id']]['meta_title'] : ''; ?>" placeholder="<?php echo $text_meta_title; ?>" id="input-meta-title<?php echo $language['language_id']; ?>" class="form-control" />
				</div>
			  </div>
			  
			  <div class="form-group">
				<label class="col-sm-2 control-label" for="input-meta-h1<?php echo $language['language_id']; ?>"><?php echo $text_meta_h1; ?></label>
				<div class="col-sm-10">
				  <input type="text" name="poll[<?php echo $language['language_id']; ?>][meta_h1]" value="<?php echo isset($poll[$language['language_id']]) ? $poll[$language['language_id']]['meta_h1'] : ''; ?>" placeholder="<?php echo $text_meta_h1; ?>" id="input-meta-h1<?php echo $language['language_id']; ?>" class="form-control" />
				</div>
			  </div>
			  
			  <div class="form-group">
				<label class="col-sm-2 control-label" for="input-meta-keyword<?php echo $language['language_id']; ?>"><?php echo $text_meta_keyword; ?></label>
				<div class="col-sm-10">
				  <input type="text" name="poll[<?php echo $language['language_id']; ?>][meta_keyword]" value="<?php echo isset($poll[$language['language_id']]) ? $poll[$language['language_id']]['meta_keyword'] : ''; ?>" placeholder="<?php echo $text_meta_keyword; ?>" id="input-meta-keyword<?php echo $language['language_id']; ?>" class="form-control" />
				</div>
			  </div>
			  
			</div>
			<?php } ?>
		  </div>
		  
		  <div class="form-group">
			<label class="col-sm-2 control-label" for="input-date-start"><?php echo $text_date_start; ?></label>
			<div class="col-sm-3">
				<div class="input-group datetime">
					<input type="text" name="date_start" value="<?php echo $date_start; ?>" id="input-date-start" placeholder="<?php echo $text_date_start; ?>" data-date-format="YYYY-MM-DD HH:mm:ss" required class="form-control" />
                    <span class="input-group-btn">
						<button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
                    </span>				
				</div>
			</div>
		  </div>
		  
		  <div class="form-group">
			<label class="col-sm-2 control-label" for="input-date-end"><?php echo $text_date_end; ?></label>
			<div class="col-sm-3">
				<div class="input-group datetime">
					<input type="text" name="date_end" value="<?php echo $date_end; ?>" id="input-date-end" placeholder="<?php echo $text_date_end; ?>" data-date-format="YYYY-MM-DD HH:mm:ss" required class="form-control" />
                    <span class="input-group-btn">
						<button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
                    </span>				
				</div>
			</div>
		  </div>
		  
		  
		  <div class="form-group">
			<label class="col-sm-2 control-label" for="input-status"><?php echo $text_status; ?></label>
			<div class="col-sm-10">
			  <select name="status" id="input-status" class="form-control">
				<?php if ($status) { ?>
				<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
				<option value="0"><?php echo $text_disabled; ?></option>
				<?php } else { ?>
				<option value="1"><?php echo $text_enabled; ?></option>
				<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
				<?php } ?>
			  </select>
			</div>
		  </div>
		  
		  <div class="form-group">
			<label class="col-sm-2 control-label" for="input-status"><?php echo $text_sort_order; ?></label>
			<div class="col-sm-10">
				<input type="text" name="sort_order" value="<?php echo $sort_order; ?>" placeholder="<?php echo $text_sort_order; ?>" class="form-control" />
			</div>
		  </div>

		  
		  <div class="panel panel-default">
			<div class="panel-heading"><?php echo $text_answer; ?></div>
			<div class="panel-body" id="answers-body">
				<?php $answer_row = 0; ?>
				<?php foreach ($answers as $answer) { ?>
					<div class="form-group" id="answer-row-<?php echo $answer_row; ?>">
						<input type="hidden" name="answers[<?php echo $answer_row; ?>][answer_id]" value="<?php echo $answer['answer_id']; ?>">
						<div class="col-sm-6">
							<label><?php echo $text_answer; ?>:</label>
							<?php foreach ($languages as $language) { ?>
								<div class="input-group">
									<span class="input-group-addon"><img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" /></span>
									<input type="text" name="answers[<?php echo $answer_row; ?>][description][<?php echo $language['language_id']; ?>][name]" class="form-control" value="<?php echo $answer['description'][$language['language_id']]['name']; ?>" <?php echo ($votes) ? 'readonly="readonly"' : ''; ?>>
								</div>
							<?php } ?>
						</div>
						<div class="col-sm-4">
							<label for="answer-sort-<?php echo $answer_row; ?>"><?php echo $text_sort_order; ?>:</label>
								<input type="text" name="answers[<?php echo $answer_row; ?>][sort_order]" placeholder="<?php echo $text_sort_order; ?>" class="form-control" id="answer-sort-<?php echo $answer_row; ?>" value="<?php echo $answer['sort_order']; ?>" />
						</div>
						<?php if($votes==0) { ?>
							<div class="col-sm-2 text-right"><br />
								<button type="button" data-toggle="tooltip" class="btn btn-primary" onclick="$('#answer-row-<?php echo $answer_row; ?>').remove();" title="<?php echo $button_del_answer; ?>" ><i class="fa fa-minus-circle"></i></button>
							</div>
						<?php } else { ?>
							<div class="col-sm-2">
								<label><?php echo $text_votes; ?>:</label>
								<input type="text" class="form-control"  name="answers[<?php echo $answer_row; ?>][vote]" value="<?php echo $answer['vote']; ?>" readonly="readonly" />
							</div>
						<?php } ?>
					</div>
					<?php $answer_row++; ?>
				<?php } ?>
				
			</div>
			<div class="panel-footer text-right">
			<?php if($votes==0) { ?>
				<button type="button" form="form-poll" data-toggle="tooltip" class="btn btn-primary" onclick="addAnswer()" ><?php echo $button_add_answer; ?></button>
			<?php } ?>
			</div>
		  </div>		  
		  
        </form>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript"><!--
	var answer_row = <?php echo $answer_row; ?>;

	function addAnswer() {
		html  = '<div class="form-group" id="answer-row-' + answer_row + '">';
		html += '	<div class="col-sm-6">';
		html += '		<label>Ответ:</label>';
		<?php foreach ($languages as $language) { ?>
			html += '	<div class="input-group">';
			html += '		<span class="input-group-addon"><img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" /></span>';
			html += '		<input type="text" name="answers[' + answer_row + '][description][<?php echo $language['language_id']; ?>][name]" class="form-control">';
			html += '	</div>';
		<?php } ?>
		html += '	</div>';
		html += '	<div class="col-sm-4">';
		html += '		<label for="answer-sort-' + answer_row + '"><?php echo $text_sort_order; ?>:</label>';
		html += '		<input type="text" name="answers[' + answer_row + '][sort_order]" value="' + answer_row + '" placeholder="<?php echo $text_sort_order; ?>" class="form-control" id="answer-sort-' + answer_row + '" />';
		html += '	</div>';
		html += '	<div class="col-sm-2 text-right"><br />';
		html += '		<button type="button" data-toggle="tooltip" class="btn btn-primary" onclick="$(\'#answer-row-' + answer_row + '\').remove();" title="<?php echo $button_del_answer; ?>" ><i class="fa fa-minus-circle"></i></button>';
		html += '	</div>';
		html += '</div>';

		$('#answers-body').append(html);

		answer_row++;
	}


	$('.datetime').datetimepicker({
		pickDate: true,
		pickTime: true
	});

	$('#language a:first').tab('show');

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
	

<?php echo $footer; ?>