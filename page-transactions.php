<?php
    no_displayed_error_result($transactions, multichain('listwallettransactions', 100, 0, false, true));
?>

<!-- <pre>
<?php
//print_r(array_reverse($transactions));
    $transactions = array_reverse($transactions);
?>
</pre> -->

<table class="table table-bordered table-condensed table-break-words table-striped">
  <thead>
    <th>Transaction ID</th>
    <th>Block Hash</th>
    <th>From</th>
    <th>To</th>
    <th>Amount (ICX)</th>
  </thead>
  <?php foreach ($transactions as $tx): ?>
    <tr>
      <td><?php echo $tx['txid']; ?></td>
      <td><?php echo $tx['blockhash']; ?></td>
      <td><?php echo $tx['myaddresses'][0]; ?></td>
      <td><?php echo $tx['myaddresses'][1]; ?></td>
      <td><?php echo $tx['vout'][0]['assets'][0]['qty']; ?></td>
    </tr>
  <?php endforeach; ?>
</table>
