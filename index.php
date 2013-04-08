<?php
require 'phpFb/phpFb.php';
$phpFb = new phpFb();
$url = $phpFb->loadFb(0);
$naitik = $phpFb->getUserData('naitik');
var_dump($phpFb->checkLikePage());
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

    <?php if ($phpFb->user_profile): ?>
      <a href="<?php echo $url; ?>">Logout</a>
    <?php else: ?>
      <div>
        Login using OAuth 2.0 handled by the PHP SDK:
        <a href="<?php echo $url; ?>">Login with Facebook</a>
      </div>
    <?php endif ?>

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