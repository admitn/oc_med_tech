<div class="panel panel-default" id="poll<?php echo $module; ?>">
  <style>
	#poll<?php echo $module; ?> .radio { width: 100%; padding: 0; margin: 0; }
	#poll<?php echo $module; ?> .radio label { width: 100%; padding: 5px 10px 5px 30px; margin: 0; }
	#poll<?php echo $module; ?> .radio label:hover { background: #F0F0F0; }
	#poll<?php echo $module; ?> .badge { font-weight: 200; }
	
	#poll<?php echo $module; ?> .panel-body {
		position: relative;
	}
	#poll<?php echo $module; ?> .panel-body .sdesc {
		padding: 3px 0 3px 0;
		margin-bottom: 5px;
		line-height: 125%;
	}
	#poll<?php echo $module; ?> .panel-body #public_update {
		position: absolute;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		background: rgba(0,0,0,.15);
		z-index: 10;
		display: none;
	}
	#poll<?php echo $module; ?> .panel-body #public_update img {
		position: absolute;
		top: calc(50% - 20px);
		left: calc(50% - 20px);
	}
	#poll<?php echo $module; ?> .panel-footer small {
		display: inline-block;
		line-height: 120%;
	}
	
  </style>

  <div class="panel-heading"> <?php echo $heading_title; ?> 
	<span class="badge pull-right" id="votes<?php echo $module; ?>"><?php echo $poll['votes']; ?></span>
  </div>
  <form>
  <div class="panel-body">
	
	<div id="public_update"><img src="/catalog/view/theme/default/image/poll_loading.gif" alt="" /></div>
	
	<?php if( !empty($poll['href']) || !empty($poll['small_description']) ) { ?>
	<div class="sdesc">
		<?php if( !empty($poll['href']) ) { ?>
			<a href="<?php echo $poll['href']; ?>" title="<?php echo $text_detailed; ?>"><?php echo !empty($poll['small_description']) ? trim($poll['small_description']) : $text_detailed; ?></a>
		<?php } elseif( !empty($poll['small_description']) ) { ?>
			<?php echo trim($poll['small_description']); ?>
		<?php } ?>
	</div>
	<?php } ?>
	
	<?php foreach($answers as $answer) { ?>
		<div class="radio width100">
		  <label>
			<input type="radio" name="answer" id="answer<?php echo $answer['answer_id']; ?>" value="<?php echo $answer['answer_id']; ?>" <?php echo ($poll_enable ? '' : 'disabled'); ?> <?php echo ($MyVote['answer_id']==$answer['answer_id'] ? 'checked' : ''); ?> >
			<?php echo $answer['description'][$language_id]['name']; ?>
			<span class="badge pull-right" id="vote<?php echo $answer['answer_id']; ?>"><?php echo $answer['vote']; ?></span>
		  </label>
		</div>		
	<?php } ?>
	
	
  </div>
  </form>
  <div class="panel-footer">
    <div class="row">
      <?php if( !$isLogged ) { ?>
        <div class="col-sm-12 text-center">
            <small><?php echo $text_login; ?></small>
        </div>
      <?php } else { ?>
        <div class="col-xs-12 col-sm-6 PollVoteText"><small><?php echo (empty($MyVote['answer_id'])) ? $text_not_voted :'' ; ?></small></div>
        <div class="col-xs-12 col-sm-6 text-right">
          <?php if($poll['date_start'] <= $datatime && $poll['date_end'] >= $datatime && $isLogged ) { ?>
            <button type="button" id="btn<?php echo $module; ?>" class="btn btn-default" disabled ><?php echo $button_votes; ?></button>
               
          <?php } else if ($poll['date_start'] > $datatime) { ?>
            <small><?php echo $text_date_start; ?></small>
          <?php } else if ($poll['date_end'] < $datatime) { ?>
            <small><?php echo $text_date_end; ?></small>
          <?php } ?>
        </div>
      <?php } ?>
    </div>
  </div>
  
	<div class="modal fade" id="ModalPollVoteText<?php echo $module; ?>">
	  <div class="modal-dialog">
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4 class="modal-title"><?php echo $text_dialog_title; ?></h4>
		  </div>
		  <div class="modal-body">
			<p><?php echo $text_dialog_text; ?></p>
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		  </div>
		</div><!-- /.modal-content -->
	  </div><!-- /.modal-dialog -->
	</div><!-- /.modal -->	
  
<script type="text/javascript"><!--

	$('#poll<?php echo $module; ?> input[name="answer"]').on('change', function(){
		var _chek = $('#poll<?php echo $module; ?> input[name="answer"]:checked').val();
		if( _chek == 'undefined' ) {
			$('#btn<?php echo $module; ?>').prop('disabled', true).removeClass('btn-primary');
		} else {
			$('#btn<?php echo $module; ?>').prop('disabled', false).addClass('btn-primary');
		}
	});

	$('#btn<?php echo $module; ?>').on('click', function(){
		var answer_id = $('#poll<?php echo $module; ?> input[name="answer"]:checked').val();
		$('#btn<?php echo $module; ?>').prop('disabled', true).removeClass('btn-primary');
		$.ajax({
			url: 'index.php?route=extension/module/poll/update&poll_id=<?php echo $poll['poll_id'] ; ?>&answer_id=' +  answer_id,
			dataType: 'json',
			beforeSend: function() {
				$('#poll<?php echo $module; ?> #public_update').show(0);
			},
			complete: function() {
				$('#poll<?php echo $module; ?> #public_update').hide(0);
			},				
			success: function(json) {
				if (json['votes']) {
					$('#poll<?php echo $module; ?> #votes<?php echo $module; ?>').html(json['votes']);
				}
				if (json['answers']) {
					$.each(json['answers'], function(k, val) {
						$('#poll<?php echo $module; ?> #vote' + val.answer_id).html( val.vote );
					});
				}
				if (json['succes']=='done') {
                    $('#poll<?php echo $module; ?> .PollVoteText').text('');
					$('#ModalPollVoteText<?php echo $module; ?>').modal('show');
				}			
				if (json['error']) {
				}			
			}
		});
	});

--></script>
  
  
</div>	




