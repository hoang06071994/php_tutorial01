<?php
if (!defined('_INCODE')) die('access deined ...');
?>
<div style="text-align: center;">
    <h2 style="color: red;">Lỗi liên quan đến CSDL</h2>
    <hr>
    <p><?php echo $exception->getMessage(); ?></p>
    <p>File: <?php echo $exception->getFile(); ?></p>
    <p>Line: <?php echo $exception->getLine(); ?></p>
</div>