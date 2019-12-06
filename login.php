<?php


require './inc/config.inc.php';


$info = [
    'tab_title' => 'Login',
    'page_title' => 'Login'
];

require './inc/header.inc.php';
?>

<h1><?= $info['page_title']; ?></h1>
<hr>

<form action="<?= base_url(); ?>index.php?route=/" method="POST">
    <label>Token:</label>
    <br>
    <input name="token" placeholder="Token">
    <br><br>
    <input type="submit" value="Login">
</form>


<?php
require './inc/footer.inc.php';
