<?php echo $header; echo $column_left; ?>
<div id="content" class="ntcd">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="ntcd--form" data-toggle="tooltip" title="<?php echo $common_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $common_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
      </div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb): ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php endforeach; ?>
      </ul>
    </div>
  </div>

  <div class="container-fluid">
    <?php foreach ($errors as $e): ?>
    <?php if ($e): ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $e; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php endif; ?>
    <?php endforeach; ?>

    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $common_edit; ?></h3>
      </div>
      <div class="panel-body">

        <?php if ($show_timezone_warning): ?>
        <div class="alert alert-warning ntcd--timezone-warning">
          <?php echo $common_timezone_warning; ?>
          <div class="ntcd--alert-btns">
            <button class="btn btn-default btn-sm ntcd--close-forever" type="button" data-dismiss="alert"><?php echo $common_timezone_warning_close_never; ?></button>
            <button class="btn btn-primary btn-sm" type="button" data-dismiss="alert"><?php echo $common_timezone_warning_close; ?></button>
          </div>
        </div>
        <?php endif; ?>

        <!-- <script> window.NTCDServiceOff = true; </script> -->
        <div class="ntcd--notification-service" data-except="<?php echo $notification_except; ?>">
          <template>
            <div class="alert"><button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span></button></div>
          </template>
        </div>

        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="ntcd--form" class="form-horizontal">
          <div class="form-group">
            <label class="col-lg-2 col-md-3 col-sm-4 control-label" for="ntcd--name"><?php echo $form_name; ?>: </label>
            <div class="col-lg-10 col-md-9 col-sm-8">
              <input type="text" class="form-control" id="ntcd--name" name="name" value="<?php echo $name; ?>" maxlength="200" placeholder="<?php echo $form_name; ?>">
            </div>
          </div>

          <div class="form-group">
            <div class="col-lg-2 col-md-3 col-sm-4"><b class="pull-right"><?php echo $form_view; ?></b></div>
            <div class="col-lg-10 col-md-9 col-sm-8">
              <?php foreach ($views as $view): ?>
              <div class="radio">
                <label><input type="radio" name="view" value="<?php echo $view['value']; ?>"<?php if ($view['value'] == $current_view) echo ' checked'; ?>> <?php echo $view['label']; ?></label>
              </div>
              <?php endforeach; ?>
            </div>
          </div>

          <div class="form-group">
            <div class="col-lg-2 col-md-3 col-sm-4">
              <b class="pull-right"><?php echo $form_layouts; ?></b>
              <br>
              <div class="ntcd--icon-help help-block"><i class="fa fa-info-circle"></i> <span><?php echo $form_layouts_help; ?></span></div>
            </div>
            <div class="col-lg-10 col-md-9 col-sm-8">
              <?php foreach ($layouts as $layout): ?>
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="layout-<?php echo $layout['id']; ?>"<?php if ($layout['checked']) echo ' checked'; ?>>
                  <?php if ($layout['suggested']) echo '<b>'; echo $layout['label']; if ($layout['suggested']) echo '</b>'; ?>
                </label>
              </div>
            <?php endforeach; ?>
            </div>
          </div>

          <div class="form-group">
            <div class="col-lg-10 col-md-9 col-sm-8 col-lg-offset-2 col-md-offset-3 col-sm-offset-4">
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="featured"<?php if ($featured_checked) echo ' checked'; ?>>
                  <?php echo "<b>$form_featured_label</b>"; ?>
                </label>
              </div>
            </div>
          </div>

          <div class="form-group">
            <label class="col-lg-2 col-md-3 col-sm-4 control-label" for="ntcd--status"><?php echo $form_status; ?></label>
            <div class="col-lg-10 col-md-9 col-sm-8">
              <select name="status" id="ntcd--status" class="form-control">
                <?php if ($status): ?>
                <option value="1" selected><?php echo $form_enabled; ?></option>
                <option value="0"><?php echo $form_disabled; ?></option>
                <?php else: ?>
                <option value="1"><?php echo $form_enabled; ?></option>
                <option value="0" selected><?php echo $form_disabled; ?></option>
                <?php endif; ?>
              </select>
            </div>
          </div>

          <div class="form-group">
            <div class="col-lg-2 col-md-3 col-sm-4">
              <b class="pull-right"><?php echo $form_clock_label; ?></b>
              <br>
              <div class="ntcd--icon-help help-block"><i class="fa fa-exclamation-circle"></i> <span><?php echo $form_adv_setting; ?></span></div>
            </div>
            <div class="col-lg-10 col-md-9 col-sm-8">
              <div class="radio">
                <label><input type="radio" name="clock" value="browser"<?php if ($clock == 'browser') echo ' checked'; ?>> <?php echo $form_clock_browser_label; ?></label>
                <span class="help-block"><?php echo $form_clock_browser_help; ?></span>
              </div>
              <div class="radio">
                <label><input type="radio" name="clock" value="server"<?php if ($clock == 'server') echo ' checked'; ?>> <?php echo $form_clock_server_label; ?></label>
                <span class="help-block"><?php echo $form_clock_server_help; ?></span>
              </div>
              <div class="radio">
                <label><input type="radio" name="clock" value="combo"<?php if ($clock == 'combo') echo ' checked'; ?>> <?php echo $form_clock_combo_label; ?></label>
                <span class="help-block"><?php echo $form_clock_combo_help; ?></span>
                <label><?php echo $form_clock_critdiff; ?> <input type="number" name="clock--critdiff" value="<?php echo $clock__critdiff; ?>" min="0" max="2147483647"></label>
              </div>
            </div>
          </div>
        </form>
      </div>
  </div>
</div>
</div>
<?php echo $footer; ?>