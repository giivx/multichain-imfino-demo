<?php
  no_displayed_error_result($transactions, multichain('listwallettransactions', 100, 0, false, true));
?>

<table class="table table-bordered table-condensed table-break-words table-striped">

<?php
  $transactions = array_reverse($transactions)

  print("<pre>".print_r($transactions,true)."</pre>");
  //foreach ($transactions as $tx)
 ?>

</table>
