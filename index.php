<?php
require("inc/include.php");

$h1 = $system["site"]["name"] . ($system["site"]["viceName"] ? " - " . $system["site"]["viceName"] : "");

$dataHelper = new dataHelper("categories");
$dataHelper->sort("!sort");
$categories = $dataHelper->data;
$dataHelper = new dataHelper("sites");
$dataHelper->filter("status", ["0", NULL]);
$dataHelper->sort("!sort");
$sites = $dataHelper->data;

$pageOptions->quickGuides[] = ["name" => "申请收录", "url" => "board.php"];
foreach ($categories as $key => $value) {
    $sites = $dataHelper->getAll("category", $value["id"]);
    if (count($sites)) {
        $pageOptions->quickGuides[] = ["name" => $value["name"] . " (" . count($sites) . ")", "navto" => $key];
        $strData .= "<a name=\"$key\"></a>";
        $strData .= "<div class=\"category\">";
        $strData .= languageHelper::transfer($value["name"]);
        $strData .= $value["intro"] ? "<span>" . $value["intro"] . "</span>" : "";
        $strData .= "</div>";
        $strData .= "<div class=\"sites\" showType=\"" . $value["showType"] . "\">";
        foreach ($sites as $_key => $_value) {
            if ($value["showType"] == 1) {
                $strData .= "<div class=\"item\">";
                $strData .= "<div><img src=\"images/flag/" . $_value["flag"] . ".png\" /></div>";
                $strData .= "<div>";
                $strData .= "<div class=\"name\">";
                $strData .= "<a title=\"" . languageHelper::transfer($_value["name"]) . "\" href=\"" . $_value["url"] . "\"" . ($_value["dofollow"] ? "" : " rel=\"nofollow\"") . " target=\"_blank\">";
                $strData .= languageHelper::transfer($_value["name"]) . "<span>" . commonHelper::formatUrl($_value["urlForShow"] ? $_value["urlForShow"] : $_value["url"]) . "</span>";
                $strData .= "</a>";
                $strData .= "</div>";
                $strData .= $_value["intro"] ? "<div class=\"intro\">" . languageHelper::transfer($_value["intro"]) . "</div>" : "";
                $strData .= "<div class=\"thumbs\">";
                $strData .= "<a objid=\"" . $_value["id"] . "\" name=\"thumbs\" value=\"up\" title=\"" . languageHelper::transfer("赞一下") . "\"><i class=\"fa fa-thumbs-up\"></i><span value=\"up\">" . $_value["thumbsUp"] . "</span></a>";
                $strData .= "<a objid=\"" . $_value["id"] . "\" name=\"thumbs\" value=\"down\" title=\"" . languageHelper::transfer("踩一下") . "\"><i class=\"fa fa-thumbs-down\"></i><span value=\"down\">" . $_value["thumbsDown"] . "</span></a>";
                $strData .= "</div>";
                $strData .= "</div>";
                $strData .= "</div>";
            } else {
                $strData .= "<a title=\"" . languageHelper::transfer($_value["name"]) . "\" href=\"" . $_value["url"] . "\"" . ($_value["dofollow"] ? "" : " rel=\"nofollow\"") . " target=\"_blank\">";
                $strData .= "<div class=\"item\">";
                $strData .= "<div><img src=\"images/flag/" . $_value["flag"] . ".png\" /></div>";
                $strData .= "<div>";
                $strData .= languageHelper::transfer($_value["name"]);
                $strData .= "</div>";
                //$strData .= $_value["intro"] ? "<div class=\"intro\">" . languageHelper::transfer($_value["intro"]) . "</div>" : "";
                $strData .= "</div>";
                $strData .= "</a>";
            }
        }
        $strData .= "</div>";
    }
}

require_once("inc/header.php");
?>
<script>
    $(document).ready(function () {
        $("a[name='thumbs']").click(function () {
            var value = $(this).attr("value");
            var number = parseInt($("span", $(this)).text());
            var objid = $(this).attr("objid");
            my.ajax({
                action: "thumbs",
                id: objid,
                value: value
            }, function (result) {
                switch (result.code) {
                    case 0:
                        my.success("<?php languageHelper::out("操作成功，感谢您的投票") ?>");
                        $(".item .thumbs > a[objid='" + objid + "'] > span[value='" + value + "']").text(number + 1);
                        break;
                    default:
                        my.error(result.message);
                        break;
                }
            });
        });
    });
</script>
<div class="box">
    <div class="sex">
        <img src="images/sex.png" />
    </div>
    <h1>
        <?php languageHelper::out($h1) ?>
    </h1>
    <h2>
        <?php languageHelper::out($system["appearance"]["h2"]) ?>
    </h2>
</div>
<div class="box">
    <?php echo $strData ?>
</div>

<?php
require_once("inc/footer.php");
?>