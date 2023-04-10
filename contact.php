<?php
require("inc/include.php");

$pageOptions->webTitle = "联系我们";

$dataHelper = new dataHelper("pages");
$page = $dataHelper->getOne("id", "contact");

require_once("inc/header.php");
?>
<style>
    .box {
        min-height: 500px;
    }
</style>
<div class="box">
    <div class="sex">
        <img src="images/sex.png" />
    </div>
    <h1>
        <?php languageHelper::out("联系我们") ?>
    </h1>
    <div>
        <?php languageHelper::out($page["content"]) ?>
    </div>
</div>


<?php
require_once("inc/footer.php");
?>