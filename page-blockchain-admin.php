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
      <h3>Wallets List - Addresses on the Blockchain</h3>

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
      (isset($label) ? 'change wallet label' : 'Set wallet label').
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
            <input class="btn btn-default" name="getnewaddress" type="submit" value="Create new wallet">
          </div>
        </div>
      </form>
    </div>
  </div>

  <?php
  	if (@$_POST['unlockoutputs'])
  		if (no_displayed_error_result($result, multichain('lockunspent', true)))
  			output_success_text('All outputs successfully unlocked');

  	if (@$_POST['sendasset']) {
  		if (strlen($_POST['metadata']))
  			$success=no_displayed_error_result($sendtxid, multichain('sendwithmetadatafrom',
  				$_POST['from'], $_POST['to'], array($_POST['asset'] => floatval($_POST['qty'])), bin2hex($_POST['metadata'])));
  		else
  			$success=no_displayed_error_result($sendtxid, multichain('sendassetfrom',
  				$_POST['from'], $_POST['to'], $_POST['asset'], floatval($_POST['qty'])));

  		if ($success)
  			output_success_text('Asset successfully sent in transaction '.$sendtxid);
  	}
  ?>

  			<div id="icxsend" class="row">

  				<div class="col-sm-5">
  					<h3>Available Balance</h3>

  <?php
  	$sendaddresses=array();
  	$usableaddresses=array();
  	$keymyaddresses=array();
  	$keyusableassets=array();
  	$haslocked=false;
  	$getinfo=multichain_getinfo();
  	$labels=array();

  	if (no_displayed_error_result($getaddresses, multichain('getaddresses', true))) {

  		if (no_displayed_error_result($listpermissions,
  			multichain('listpermissions', 'send', implode(',', array_get_column($getaddresses, 'address')))
  		))
  			$sendaddresses=array_get_column($listpermissions, 'address');

  		foreach ($getaddresses as $address)
  			if ($address['ismine'])
  				$keymyaddresses[$address['address']]=true;

  		$labels=multichain_labels();

  		if (no_displayed_error_result($listpermissions, multichain('listpermissions', 'receive')))
  			$receiveaddresses=array_get_column($listpermissions, 'address');

  		foreach ($sendaddresses as $address) {
  			if (no_displayed_error_result($allbalances, multichain('getaddressbalances', $address, 0, true))) {

  				if (count($allbalances)) {
  					$assetunlocked=array();

  					if (no_displayed_error_result($unlockedbalances, multichain('getaddressbalances', $address, 0, false))) {
  						if (count($unlockedbalances))
  							$usableaddresses[]=$address;

  						foreach ($unlockedbalances as $balance)
  							$assetunlocked[$balance['name']]=$balance['qty'];
  					}

  					$label=@$labels[$address];

  ?>
  						<table class="table table-bordered table-condensed table-break-words <?php echo ($address==@$getnewaddress) ? 'bg-success' : 'table-striped'?>">
  <?php
  			if (isset($label)) {
  ?>
  							<tr>
  								<th style="width:25%;">Label</th>
  								<td><?php echo html($label)?></td>
  							</tr>
  <?php
  			}
  ?>
  							<tr>
  								<th style="width:20%;">Address</th>
  								<td class="td-break-words small"><?php echo html($address)?></td>
  							</tr>
  <?php
  					foreach ($allbalances as $balance) {
  						$unlockedqty=floatval($assetunlocked[$balance['name']]);
  						$lockedqty=$balance['qty']-$unlockedqty;

  						if ($lockedqty>0)
  							$haslocked=true;
  						if ($unlockedqty>0)
  							$keyusableassets[$balance['name']]=true;
  ?>
  							<tr>
  								<th><?php echo html($balance['name'])?></th>
  								<td><?php echo html($unlockedqty)?><?php echo ($lockedqty>0) ? (' ('.$lockedqty.' locked)') : ''?></td>
  							</tr>
  <?php
  					}
  ?>
  						</table>
  <?php
  				}
  			}
  		}
  	}

  	if ($haslocked) {
  ?>
  				<form class="form-horizontal" method="post" action="./?chain=<?php echo html($_GET['chain'])?>&page=<?php echo html($_GET['page'])?>">
  					<input class="btn btn-default" type="submit" name="unlockoutputs" value="Unlock all outputs">
  				</form>
  <?php
  	}
  ?>
  				</div>

  				<div class="col-sm-7">
  					<h3>Send ICX</h3>

  					<form class="form-horizontal" method="post" action="./?chain=<?php echo html($_GET['chain'])?>&page=<?php echo html($_GET['page'])?>#icxsend">
  						<div class="form-group">
  							<label for="from" class="col-sm-3 control-label">From address:</label>
  							<div class="col-sm-9">
  							<select class="form-control" name="from" id="from">
  <?php
  	foreach ($usableaddresses as $address) {
  ?>
  								<option value="<?php echo html($address)?>"><?php echo format_address_html($address, true, $labels)?></option>
  <?php
  	}
  ?>
  							</select>
  							</div>
  						</div>
  						<div class="form-group">
  							<label for="asset" class="col-sm-3 control-label">Asset name:</label>
  							<div class="col-sm-9">
  							<select class="form-control" name="asset" id="asset">
  <?php
  	foreach ($keyusableassets as $asset => $dummy) {
  ?>
  								<option value="<?php echo html($asset)?>"><?php echo html($asset)?></option>
  <?php
  	}
  ?>
  							</select>
  							</div>
  						</div>
  						<div class="form-group">
  							<label for="to" class="col-sm-3 control-label">To address:</label>
  							<div class="col-sm-9">
  							<select class="form-control" name="to" id="to">
  <?php
  	foreach ($receiveaddresses as $address) {
  		if ($address==$getinfo['burnaddress'])
  			continue;
  ?>
  								<option value="<?php echo html($address)?>"><?php echo format_address_html($address, @$keymyaddresses[$address], $labels)?></option>
  <?php
  	}
  ?>
  							</select>
  							</div>
  						</div>
  						<div class="form-group">
  							<label for="qty" class="col-sm-3 control-label">Quantity:</label>
  							<div class="col-sm-9">
  								<input class="form-control" name="qty" id="qty" placeholder="0.0">
  							</div>
  						</div>
  						<!--<div class="form-group">
  							<label for="metadata" class="col-sm-3 control-label">Metadata:</label>
  							<div class="col-sm-9">
  								<textarea class="form-control" rows="3" name="metadata" id="metadata"></textarea>
  							</div>
  						</div>-->
  						<div class="form-group">
  							<div class="col-sm-offset-3 col-sm-9">
  								<input class="btn btn-default" type="submit" name="sendasset" value="Send ICX">
  							</div>
  						</div>
  					</form>

  				</div>
  			</div>

</div>
