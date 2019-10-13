<?php
  if (@$_POST['getnewaddress'])
      no_displayed_error_result($getnewaddress, multichain('getnewaddress'));
?>
<div class="container">
	<div class="row">
    <h1>Blockchain Administrator</h1>
    <p>Here you can create new addresses and issue ICX coins.</p>
  </div>
  <div class="row">
    <div class="col-xs-12">
      <h3>Current Node Addresses</h3>

<?php
if (no_displayed_error_result($getaddresses, multichain('getaddresses', true))) {
$addressmine=array();

foreach ($getaddresses as $getaddress)
  $addressmine[$getaddress['address']]=$getaddress['ismine'];

$addresspermissions=array();

if (no_displayed_error_result($listpermissions,
  multichain('listpermissions', 'all', implode(',', array_keys($addressmine)))
))
  foreach ($listpermissions as $listpermission)
    $addresspermissions[$listpermission['address']][$listpermission['type']]=true;

no_displayed_error_result($getmultibalances, multichain('getmultibalances', array(), array(), 0, true));

$labels=multichain_labels();

foreach ($addressmine as $address => $ismine) {
  if (count(@$addresspermissions[$address]))
    $permissions=implode(', ', @array_keys($addresspermissions[$address]));
  else
    $permissions='none';

  $label=@$labels[$address];
  $cansetlabel=$ismine && @$addresspermissions[$address]['send'];

  if ($ismine && !$cansetlabel)
    $permissions.=' (cannot set label)';
?>
        <table class="table table-bordered table-condensed table-break-words <?php echo ($address==@$getnewaddress) ? 'bg-success' : 'table-striped'?>">
<?php
  if (isset($label) || $cansetlabel) {
?>
          <tr>
            <th style="width:30%;">Label</th>
            <td><?php echo html(@$label)?><?php

    if ($cansetlabel)
      echo (isset($label) ? ' &ndash; ' : '').
      '<a href="'.chain_page_url_html($chain, 'label', array('address' => $address)).'">'.
      (isset($label) ? 'change label' : 'Set label').
      '</a>';

            ?></td>
          </tr>
<?php
  }
?>
          <tr>
            <th style="width:30%;">Address</th>
            <td class="td-break-words small"><?php echo html($address)?><?php echo $ismine ? '' : ' (watch-only)'?></td>
          </tr>
          <tr>
            <th>Permissions</th>
            <td><?php echo html($permissions)?><?php

      echo ' &ndash; <a href="'.chain_page_url_html($chain, 'permissions', array('address' => $address)).'">change</a>';

          ?></td></tr>
<?php
  if (isset($getmultibalances[$address])) {
    foreach ($getmultibalances[$address] as $addressbalance) {
?>
          <tr>
            <th><?php echo html($addressbalance['name'])?></th>
            <td><?php echo html($addressbalance['qty'])?></td>
          </tr>
<?php
    }
  }
?>
        </table>
<?php
}
}
?>
      <form class="form-horizontal" method="post" action="<?php echo chain_page_url_html($chain, 'blockchain-admin')?>">
        <div class="form-group">
          <div class="col-xs-12">
            <input class="btn btn-default" name="getnewaddress" type="submit" value="Get new address">
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
