<?php
  no_displayed_error_result($transactions, multichain('listwallettransactions', 100, 0, false, true));
?>

<pre>
<?php
  //$transactions = array_reverse($transactions)

  print_r(array_reverse($transactions));
  //foreach ($transactions as $tx)
 ?>
</pre>

<table class="table table-bordered table-condensed table-break-words table-striped">
  <thead>
    <th>Transaction ID</th>
    <th>Valid</th>
    <th>Block Hash</th>
    <th>From</th>
    <th>To</th>
    <th>Amount (ICX)</th>
  </thead>
  <?php foreach ($transactions as $tx): ?>
    <tr>
      <td><?php $tx['txid']; ?></td>
      <td><?php boolval($tx['valid']); ?></td>
      <td><?php $tx['blockhash']; ?></td>
      <td><?php $tx['myaddresses'][0]; ?></td>
      <td><?php $tx['myaddresses'][1]; ?></td>
      <td><?php $tx['vout'][0]['assets'][0]['qty']; ?></td>
    </tr>
  <?php endforeach; ?>
</table>
