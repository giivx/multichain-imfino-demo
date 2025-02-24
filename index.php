<?php

	require_once 'functions.php';

	$config=read_config();
	$chain=@$_GET['chain'];

	if (strlen($chain))
		$name=@$config[$chain]['name'];
	else
		$name='';

	set_multichain_chain($config[$chain]);

?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<title>Imfino Blockchain Demo</title>
		<!--
			<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
			<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">
		-->
		<link rel="stylesheet" href="bootstrap.min.css">
		<link rel="stylesheet" href="styles.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
	</head>
	<body>

		<div class="container">
			<header>
				<div class="row">
					<div class="col-md-4">
						<img src="https://imfino.com/static/images/imfino_logo.png" class="logo img-responsive">
					</div>
					<div class=".col-md-4 .col-md-offset-4">
						<h1 class="text-right pr35"><a href="./">Imfino Blockchain Demo</a>
							<!-- <?php if (strlen($name)) { ?> &ndash; <?php echo html($name)?><?php } ?> -->
						</h1>
					</div>
				</div>
			</header>
			<?php
			if (strlen($chain)) {
				$name=@$config[$chain]['name'];
			?>

					<nav class="navbar navbar-default">
						<div id="navbar" class="navbar-collapse collapse">
							<ul class="nav navbar-nav">
								<li><a href="./?chain=<?php echo html($chain)?>">Node</a></li>
								<li><a href="./?chain=<?php echo html($chain)?>&page=permissions">Permissions</a></li>
								<li><a href="./?chain=<?php echo html($chain)?>&page=issue" class="pair-first">Issue Asset</a></li>
								<li><a href="./?chain=<?php echo html($chain)?>&page=update" class="pair-second">| Update</a></li>
								<li><a href="./?chain=<?php echo html($chain)?>&page=send">Send</a></li>
								<li><a href="./?chain=<?php echo html($chain)?>&page=offer" class="pair-first">Create Offer</a></li>
								<li><a href="./?chain=<?php echo html($chain)?>&page=accept" class="pair-second">| Accept</a></li>
								<li><a href="./?chain=<?php echo html($chain)?>&page=create">Create Stream</a></li>
								<li><a href="./?chain=<?php echo html($chain)?>&page=publish">Publish</a></li>
								<li><a href="./?chain=<?php echo html($chain)?>&page=view">View Streams</a></li>
			<?php
			if (multichain_has_smart_filters()) {
			?>

								<li><a href="./?chain=<?php echo html($chain)?>&page=txfilter" class="pair-first">Filters: Transaction</a></li>
								<li><a href="./?chain=<?php echo html($chain)?>&page=streamfilter" class="pair-second">| Stream</a></li>

			<?php
			}
			?>
								<li><a href="./?chain=<?php echo html($chain)?>&page=blockchain-admin">Blockchain Administrator</a></li>
								<li><a href="./?chain=<?php echo html($chain)?>&page=blockchain-user">Blockchain User</a></li>
								<li><a href="./?chain=<?php echo html($chain)?>&page=transactions">Transactions</a></li>
							</ul>
						</div>
					</nav>
<?php
		switch (@$_GET['page']) {
			case 'label':
			case 'permissions':
			case 'issue':
			case 'update':
			case 'send':
			case 'offer':
			case 'accept':
			case 'create':
			case 'publish':
			case 'view':
			case 'txfilter':
			case 'streamfilter':
			case 'approve':
			case 'asset-file':
			case 'blockchain-admin':
			case 'blockchain-user':
			case 'transactions':
				require_once 'page-'.$_GET['page'].'.php';
				break;

			default:
				require_once 'page-default.php';
				break;
		}

	} else {

?>
<div>
			<p class="lead mt35"><a href="./?chain=default&page=blockchain-admin">Blockchain Administrator</a></p>
			<p class="lead mt35"><a href="./?chain=default&page=blockchain-user">Blockchain User</a></p>
			<p class="lead mt35"><a href="./?chain=default&page=transactions">Transactions</a></p>

			<p class="lead"><br/>Available nodes:</p>

			<p>
<?php
		foreach ($config as $chain => $rpc)
			if (isset($rpc['rpchost']))
				echo '<p class="lead"><a href="./?chain='.html($chain).'">'.html($rpc['name']).'</a><br/>';
?>
			</p>
</div>
<?php
	}
?>
			<footer>
				<div class="footer-copyright">
					<div class="container">
							<img src="https://imfino.com/static/images/imfino_logo_white.png" class="footer-logo img-responsive">
							<div class="pr35 text-right">
									© Imfino 2019
							</div>
					</div>
				</div>
			</footer>
		</div>
	</body>
</html>
