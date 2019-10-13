<?php
  no_displayed_error_result($transactions, multichain('listwallettransactions', 100, 0, false, true));

  print_r($transactions);
 ?>
