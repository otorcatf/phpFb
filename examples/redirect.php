<?php
require '../phpFb/phpFb.php';
$phpFb = new phpFb();
$phpFb->loadFb();
$phpFb->tabRedirect();
$naitik = $phpFb->getUserData('naitik');
?>
<!doctype html>
<html xmlns:fb="http://www.facebook.com/2008/fbml">
<head>
    <title>php-sdk</title>
    <style>
        body {
            font-family: 'Lucida Grande', Verdana, Arial, sans-serif;
        }
        h1 a {
            text-decoration: none;
            color: #3b5998;
        }
        h1 a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<h1>phpFb</h1>

<h3>PHP Session</h3>
<pre><?php print_r($_SESSION); ?></pre>

<?php if ($phpFb->user_profile): ?>
    <h3>You</h3>
    <img src="https://graph.facebook.com/<?php echo $phpFb->user_profile['id']; ?>/picture">

    <h3>Your User Object (/me)</h3>
    <pre><?php print_r($phpFb->user_profile); ?></pre>
<?php else: ?>
    <strong><em>You are not Connected.</em></strong>
<?php endif ?>

<h3>Public profile of Naitik</h3>
<img src="https://graph.facebook.com/naitik/picture">
<?php echo $naitik['name']; ?>
</body>
</html>