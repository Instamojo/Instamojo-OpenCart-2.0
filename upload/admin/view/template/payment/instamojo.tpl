<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-free-checkout" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-free-checkout" class="form-horizontal">
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-order-status"><?php echo $entry_order_status; ?></label>
            <div class="col-sm-10">
              <select name="instamojo_order_status_id" id="input-order-status" class="form-control">
                <?php foreach ($order_statuses as $order_status) { ?>
                <?php if ($order_status['order_status_id'] == $instamojo_order_status_id) { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-sort-order"><?php echo $entry_sort_order; ?></label>
            <div class="col-sm-10">
              <input type="text" name="instamojo_sort_order" value="<?php echo $instamojo_sort_order; ?>" id="input-sort-order" class="form-control" />
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-sort-order"><?php echo $entry_text_instamojo_checkout_label; ?></label>
            <div class="col-sm-10">
              <input type="text" name="instamojo_checkout_label" value="<?php echo $instamojo_checkout_label; ?>" id="input-sort-order" class="form-control" />
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-sort-order"><?php echo $entry_text_instamojo_api_key; ?></label>
            <div class="col-sm-10">
              <input type="text" name="instamojo_api_key" value="<?php echo $instamojo_api_key; ?>" id="input-sort-order" class="form-control" />
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-sort-order"><?php echo $entry_text_instamojo_auth_token; ?></label>
            <div class="col-sm-10">
              <input type="text" name="instamojo_auth_token" value="<?php echo $instamojo_auth_token; ?>" id="input-sort-order" class="form-control" />
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-sort-order"><?php echo $entry_text_instamojo_private_salt; ?></label>
            <div class="col-sm-10">
              <input type="text" name="instamojo_private_salt" value="<?php echo $instamojo_private_salt; ?>" id="input-sort-order" class="form-control" />
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-sort-order"><?php echo $entry_text_instamojo_payment_link; ?></label>
            <div class="col-sm-10">
              <input type="text" name="instamojo_payment_link" value="<?php echo $instamojo_payment_link; ?>" id="input-sort-order" class="form-control" />
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-sort-order"><?php echo $entry_text_instamojo_custom_field; ?></label>
            <div class="col-sm-10">
              <input type="text" name="instamojo_custom_field" value="<?php echo $instamojo_custom_field; ?>" id="input-sort-order" class="form-control" />
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
            <div class="col-sm-10">
              <select name="instamojo_status" id="input-status" class="form-control">
                <?php if ($instamojo_status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>

        </form>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?> 

<!-- 













<?php echo $header; ?> <?php echo $column_left; ?>
<div id="content">
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/payment.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="form">
          <tr>
            <td><?php echo $entry_order_status; ?></td>
            <td><select name="instamojo_order_status_id">
                <?php foreach ($order_statuses as $order_status) { ?>
                <?php if ($order_status['order_status_id'] == $instamojo_order_status_id) { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select></td>
          </tr>

          <tr>
            <td><?php echo $entry_text_instamojo_checkout_label; ?></td>
            <td><input type="text" size="100" name="instamojo_checkout_label" value="<?php echo $instamojo_checkout_label; ?>"/></td>
          </tr>

          <tr>
            <td><?php echo $entry_text_instamojo_api_key; ?></td>
            <td><input type="text" size="100" name="instamojo_api_key" value="<?php echo $instamojo_api_key; ?>"/></td>
          </tr>
          <tr>
            <td><?php echo $entry_text_instamojo_auth_token; ?></td>
            <td><input type="text" size="100" name="instamojo_auth_token" value="<?php echo $instamojo_auth_token; ?>"/></td>
          </tr>
          <tr>
            <td><?php echo $entry_text_instamojo_private_salt; ?></td>
            <td><input type="text" size="100" name="instamojo_private_salt" value="<?php echo $instamojo_private_salt; ?>"/></td>
          </tr>
          <tr>
            <td><?php echo $entry_text_instamojo_payment_link; ?></td>
            <td><input type="text" size="100" name="instamojo_payment_link" value="<?php echo $instamojo_payment_link; ?>"/></td>
          </tr>
          <tr>
            <td><?php echo $entry_text_instamojo_custom_field; ?></td>
            <td><input type="text" size="20" name="instamojo_custom_field" value="<?php echo $instamojo_custom_field; ?>"/></td>
          </tr>
           
          <tr>
            <td><?php echo $entry_status; ?></td>
            <td><select name="instamojo_status">
                <?php if ($instamojo_status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select></td>
          </tr>
        </table>
      </form>
    </div>
  </div>
</div>
<?php echo $footer; ?> -->