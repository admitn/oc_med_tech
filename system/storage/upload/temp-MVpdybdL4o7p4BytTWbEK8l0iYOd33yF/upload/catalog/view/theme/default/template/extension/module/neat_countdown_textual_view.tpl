<script>
  var NTCD = NTCD || {}
  if (!NTCD.textual) NTCD.textual = {
    lang: <?php echo json_encode($lang); ?>,
    insts: {},
  };
  NTCD.textual.insts['<?php echo $inst; ?>'] = <?php echo json_encode($clock); ?>;
</script>
<div class="ntcd-textual" data-inst="<?php echo $inst; ?>" style="margin-bottom: 10px; line-height: 1.4"></div>