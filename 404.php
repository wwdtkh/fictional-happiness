<?php
require("inc/include.php");

$pageOptions->webTitle = "404错误，网页不见了";

require_once("inc/header.php");
?>

<style>
    h1 {
        font-size: 50px;
    }
    .box {
        min-height: 400px;
    }
</style>

<div class="box">
    <div class="sex">
        <img src="images/sex.png" />
    </div>
    <h1>
        404
    </h1>
    <h2>
        <?php languageHelper::out("糟糕，网页不见了") ?>
    </h2>
</div>


<?php
require_once("inc/footer.php");
?>