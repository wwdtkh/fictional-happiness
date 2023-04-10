<?php
require("inc/include.php");

$pageOptions->webTitle = "申请收录";

$dataHelper = new dataHelper("categories");
$dataHelper->sort("sort");
$strCategory = "<select id=\"SelCategory\">";
foreach ($dataHelper->data as $key => $value) {
    $strCategory .= "<option value=\"" . $value["id"] . "\">" . languageHelper::transfer($value["name"]) . "</option>";
}
$strCategory .= "</select>";

$hash = uniqid();

require_once("inc/header.php");
?>
<style>
    .form > div > div:nth-child(1) {
        flex: 0 0 150px;
    }
    .form input[type='text'], .form input[type='password'] {
        width: 500px;
    }
    .requirement {
        padding: 10px 10px 10px 25px;
    }
    .requirement li {
        line-height: 225%;
    }
    .required {
        color: #f00;
    }
    .data {
        display: none;
    }
    .board {
        display: flex;
        border: #eee 1px solid;
        margin-bottom: 15px;
    }
    .board > div:nth-child(1) {
        flex: 0 0 130px;
        justify-content: center;
        padding: 10px;
    }
    .board > div:nth-child(1) > .avatar {
        text-align: center;
    }
    .board > div:nth-child(1) > .date {
        padding-top: 15px;
        font-size: 0.9em;
        text-align: center;
    }
    .board > div:nth-child(2) {
        flex-grow: 1;
    }
    .board > div:nth-child(2) > div {
        height: 30px;
        line-height: 30px;
        border-bottom: #eee 1px dashed;
        overflow: hidden;
    }
    .board > div:nth-child(2) > div:last-child {
        border-bottom: 0;
    }

    @media screen and (max-width: 640px) {
        .avatar {
            width: 10vw;
            height: 10vw;
        }
        .board > div:nth-child(1) {
            flex: 0 0 15vw;
        }
        .board > div:nth-child(1) > .avatar {
            text-align: center;
        }
        .board > div:nth-child(1) > .date {
            padding-top: 15px;
            font-size: 0.9em;
            text-align: center;
        }
        .board > div:nth-child(2) {
            flex-grow: 1;
        }
    }
</style>
<script>
    var hash = "<?php echo $hash ?>";

    $(document).ready(function () {
        $("#ImgVerifyCode").click(function () {
            hash = my.getUniqid();
            $(this).attr("src", "inc/verifyCode.php?hash=" + hash);
        });
        $("#BtnOk").click(function () {
            my.ajax({
                action: "saveBoard",
                name: $("#TxtName").val().trim(),
                url: $("#TxtUrl").val().trim(),
                intro: $("#TxtIntro").val().trim(),
                category: $("#SelCategory").val(),
                email: $("#TxtEmail").val().trim(),
                verifyCode: $("#TxtVerifyCode").val(),
                message: $("#TxtMessage").val().trim(),
                hash: hash
            }, function (result) {
                switch (result.code) {
                    case 0:
                        my.success("<?php languageHelper::out("留言成功，请做好我站链接后等待站长回复，谢谢") ?>");
                        $("input[type='text'][name!='code']").val("");
                        getData();
                        break;
                    default:
                        my.error(result.message);
                        break;
                }
            });
        });
        getData();
    });

    var getData = function () {
        my.ajax({
            action: "getBoards"
        }, function (result) {
            showData(result);
        });
    }

    var showData = function (json) {
        if (json.length) {
            var str = "<h2><?php languageHelper::out("历史申请（只显示最新 10 条）：") ?></h2>";
            $.each(json, function (key, value) {
                str += "<div class=\"board\">";
                str += "<div>";
                str += "<div class=\"avatar\"><img src=\"images/avatar/0.jpg\" class=\"avatar\" /></div>";
                str += "<div class=\"date\">" + value.postDate + "</div>";
                str += "</div>";
                str += "<div>";
                str += "<div>网站名称：" + value.name + "</div>";
                str += "<div>网址：" + value.url + "</div>";
                str += "<div>分类：" + value.category + "</div>";
                if (value.message) {
                    str += "<div>留言：" + value.message + "</div>";
                }
                if (value.reply) {
                    str += "<div>站长回复：" + value.reply + "</div>";
                }
                str += "</div>";
                str += "</div>";
            });
            $(".data").html(str).show();
        }
    }
</script>

<div class="box">
    <div class="sex">
        <img src="images/sex.png" />
    </div>
    <h1>
        <?php languageHelper::out("在加入" . $system["site"]["name"] . "导航前，请先仔细阅读：") ?>
    </h1>
    <div class="requirement">
        <h3>
            <?php languageHelper::out("您的网站：") ?>
        </h3>
        <ul>
            <li>
                <?php languageHelper::out("必须是成人网站，简体中文/繁体中文/英语都可以") ?>
            </li>
            <li>
                <?php languageHelper::out("必须有一定质量，粗制滥造或者很久没更新的网站不会被收录") ?>
            </li>
            <li>
                <?php languageHelper::out("必须能被正常访问，一打开就是 Captcha Challenges 的不会被收录") ?>
            </li>
            <li>
                <?php languageHelper::out("不能含有弹窗、病毒和恶意代码") ?>
            </li>
            <li>
                <?php languageHelper::out("只接受顶级域名，不接受子目录（比如 www.domain.com 可以，www.domain.com/pic 不可以）") ?>
            </li>
        </ul>
        <h3>
            <?php languageHelper::out("请先做好我站链接：") ?>
        </h3>
        <ul>
            <li>
                <?php languageHelper::out("请将此链接代码放在您的网站上") ?>
            </li>
            <li>
                <input name="code" type="text" readonly="readonly" value='<a href="<?php echo $system["site"]["url"] ?>" target="_blank"><?php languageHelper::out($system["site"]["name"]) ?></a>' style="width: 45%" onfocus="this.select();" />
                <div class="button" copyvalue='<a href="<?php echo $system["site"]["url"] ?>" target="_blank"><?php languageHelper::out($system["site"]["name"]) ?></a>'><i class="fa fa-clone"></i>  <?php languageHelper::out("复制代码") ?></div>
            </li>
            <li>
                链接位置：
            </li>
            <ul>
                <li>
                    <?php languageHelper::out("对普通类型网站，要求放在<span style=\"color: #f00;\">首页友情链接位置</span>") ?>
                </li>
                <li>
                    <?php languageHelper::out("对导航站，要求正常收录，并在<span style=\"color: #f00;\">首页进行展示</span>") ?>
                </li>
            </ul>
            <li>
                <?php languageHelper::out("您把我们的链接排在第几位，每天能带来几次点击，我们并不在意，<span style=\"color: #f00;\">我们更在意您的网站是否优秀</span>，是否受访问者喜爱") ?>
            </li>
        </ul>
        <h3>
            <?php languageHelper::out("当您做好链接后，请填写：") ?>
        </h3>
        <div class="form">
            <div>
                <div>
                    <span class="required">*</span> <?php languageHelper::out("网站名称") ?>
                </div>
                <div>
                    <input id="TxtName" type="text" maxlength="12" placeholder="<?php languageHelper::out("请输入网站名称") ?>" />
                </div>
            </div>
            <div>
                <div>
                    <span class="required">*</span> <?php languageHelper::out("网址") ?>
                </div>
                <div>
                    <input id="TxtUrl" type="text" maxlength="50" placeholder="<?php languageHelper::out("请输入网站地址") ?>" />
                </div>
            </div>
            <div>
                <div>
                    <?php languageHelper::out("网站简介") ?>
                </div>
                <div>
                    <input id="TxtIntro" type="text" maxlength="100" placeholder="<?php languageHelper::out("请输入网站简介") ?>" />
                </div>
            </div>
            <div>
                <div>
                    <span class="required">*</span> <?php languageHelper::out("网站分类") ?>
                </div>
                <div>
                    <?php echo $strCategory ?>
                </div>
            </div>
            <div>
                <div>
                    <span class="required">*</span> <?php languageHelper::out("Email地址") ?>
                </div>
                <div>
                    <input id="TxtEmail" type="text" maxlength="50" placeholder="<?php languageHelper::out("请输入您的邮件地址，审核结果会通过邮件发送给您") ?>" />
                </div>
            </div>
            <div>
                <div>
                    <?php languageHelper::out("给我们留言") ?>
                </div>
                <div>
                    <input id="TxtMessage" type="text" maxlength="100" placeholder="<?php languageHelper::out("如果您有话要对我们说") ?>" />
                </div>
            </div>
            <div>
                <div>
                    <span class="required">*</span> <?php languageHelper::out("验证码") ?>
                </div>
                <div>
                    <input id="TxtVerifyCode" class="verfyCode" type="text" maxlength="4" placeholder="<?php languageHelper::out("请输入验证码") ?>" />
                    <img class="verifyCode" alt="<?php languageHelper::out("点击刷新") ?>" id="ImgVerifyCode" src="inc/verifyCode.php?hash=<?php echo $hash ?>" />
                    <?php languageHelper::out("看不清可以点击图片刷新") ?>
                </div>
            </div>
            <div>
                <div>
                    &nbsp;
                </div>
                <div>
                    <a class="button" role="button" id="BtnOk"><i class="fa fa-check"></i> <?php languageHelper::out("确认提交") ?></a>
                </div>
            </div>
        </div>    
    </div>
</div>
<div class="box data">
</div>

<?php
require_once("inc/footer.php");
?>