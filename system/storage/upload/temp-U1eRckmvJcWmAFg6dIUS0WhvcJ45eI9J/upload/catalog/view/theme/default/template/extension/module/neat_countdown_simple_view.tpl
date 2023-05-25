<script>
  var NTCD = NTCD || {};
  if (!NTCD.simple) NTCD.simple = {
    lang: { expired: '<?php echo $simple_text_expired; ?>' },
    insts: {},
  };
  NTCD.simple.insts['<?php echo $inst; ?>'] = <?php echo json_encode($clock); ?>;
</script>
<div class="ntcd-simple ntcd-simple__invisible" data-inst="<?php echo $inst; ?>">
  <div class="ntcd-simple--text"><?php echo $simple_text; ?></div>
  <div class="ntcd-simple--counter"><span class="ntcd-simple--days" title="<?php echo $simple_day_title; ?>"></span><?php echo $simple_day_abbr; ?>&nbsp;<span class="ntcd-simple--hours" title="<?php echo $simple_hour_title; ?>"></span>:<span class="ntcd-simple--mins" title="<?php echo $simple_min_title; ?>"></span>:<span class="ntcd-simple--secs" title="<?php echo $simple_sec_title; ?>"></span></div>
</div>