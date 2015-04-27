<form action="<?php echo $action; ?>" method="GET">

    <input type="hidden" name="embed" value="form">
    <input type="hidden" name="<?php echo $custom_field_name;?>" value="<?php echo $custom_field;?>"> 
    <input type="hidden" name="data_amount" value="<?php echo $data_amount;?>">
    <input type="hidden" name="data_email" value="<?php echo $data_email;?>">
    <input type="hidden" name="data_name" value="<?php echo $data_name;?>">
    <input type="hidden" name="data_phone" value="<?php echo $data_phone;?>">
    <input type="hidden" name="intent" value="buy">
    <input type="hidden" name="data_readonly" value="data_email">
    <input type="hidden" name="data_readonly" value="data_name">
    <input type="hidden" name="data_readonly" value="data_amount">
    <input type="hidden" name="data_readonly" value="data_phone">
    <input type="hidden" name="data_readonly" value="<?php echo $custom_field_name;?>">
    <input type="hidden" name="data_hidden" value="<?php echo $custom_field_name;?>">
    <input type="hidden" name="data_sign" value="<?php echo $data_sign;?>">

  <div class="buttons">
    <div class="right">
      <input type="submit" value="Confirm Order" class="button" />
    </div>
  </div>
</form