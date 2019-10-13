<?php
  no_displayed_error_result($transactions, multichain('listwallettransactions', 100, 0, false, true));
?>

<table class="table table-bordered table-condensed table-break-words table-striped">
<pre>
<?php
  //$transactions = array_reverse($transactions)

  print_r($transactions);
  //foreach ($transactions as $tx)
 ?>
</pre>
</table>
