<html>
<head>
<meta charset="UTF-8" />
<script type="text/javascript" src="view/javascript/jquery/jquery-2.1.1.min.js"></script>
<script type="text/javascript" src="view/javascript/bootstrap/js/bootstrap.min.js"></script>
<link href="view/stylesheet/bootstrap.css" type="text/css" rel="stylesheet" />
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">
<link type="text/css" href="view/stylesheet/stylesheet.css" rel="stylesheet" media="screen" />

<style>
	body 		{ padding: 10px; }
	.badge		{ font-weight: 200; }
</style>

<body>
<div id="content">

	<?php foreach( $answers as $answer) { ?>
		<div class="panel panel-default">
		  <div class="panel-heading">
			<h3 class="panel-title"><?php echo $answer['description'][$config_language_id]['name']; ?></h3>
			<span class="badge pull-right"><?php echo $answer['vote']; ?></span>
		  </div>
		  <div class="panel-body">
			<?php if( $answer['votes']) { ?>
				<div class="list-group">
				  <?php foreach( $answer['votes'] as $vote) { ?>
					<a href="<?php echo $customer_edit . $vote['customer_id']; ?>" class="list-group-item" target=_blank>
						<?php echo $vote['customer_name']; ?>
						<span class="badge pull-right"><?php echo SubStr($vote['date_modified'], 0, 16); ?></span>
						
					</a>
				  <?php } ?>
				</div>
			<?php } else { ?>
				<?php echo $text_no_votes; ?>
			<?php } ?>
		  </div>
		</div>
	<?php } ?>
    <div class="panel-footer text-right">
        <a class="button btn btn-default" href="<?php echo $clearVotes; ?>" onclick="return confirm('are u shure?') ? true : false;" ><?php echo $text_clear; ?></a>
    </div>
</div>
 <script type="text/javascript"><!--
//--></script> 

