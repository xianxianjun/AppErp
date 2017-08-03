<extend name="public/rightLayout"/>
<block name="head">

</block>
<block name="manageTitle">
    <?php echo $title;?>
</block>
<block name="content">
    <table class="qxTable" id="mytable" style="width: 100%"  cellspacing="0">
        <tr scope="col">
            <th scope="col" style="width: 150px;">
                是否是主账号:
            </th>
            <td>
                <input id="IsMasterAccount" name="IsMasterAccount" type="checkbox" <?php if(intval($data[0]["IsMasterAccount"]) == 1){?>checked="checked"<?php } ?>/>
            </td>
            <th scope="col" style="width: 150px;">
                石头加点:
            </th>
            <td>
                <input type="text" id="stoneAddtion" name="stoneAddtion" value="<?php echo floatval($data[0]["stoneAddtion"]);?>"/>
            </td>
            <th scope="col" style="width: 150px;">
                款号加点:
            </th>
            <td>
                <input type="text" id="modelAddtion" name="modelAddtion" value="<?php echo floatval($data[0]["modelAddtion"]);?>"/>
            </td>
            <th scope="col" style="width: 150px;">
                石头实际加点:
            </th>
            <td>
                <span id="stoneAddtiono"><?php echo floatval($stoneAddtiono);?></span>
            </td>
            <th scope="col" style="width: 150px;">
                款号实际加点:
            </th>
            <td>
                <span id="modelAddtiono"><?php echo floatval($modelAddtiono);?></span>
            </td>
        </tr>
    </table>
    <table class="qxTable" id="mytable" style="width: 100%"  cellspacing="0">
        <tr scope="col">

            <th scope="col" style="width: 150px;">
                所属主账号:
            </th>
            <td>
                <select id="myMasterAccountMId" name="myMasterAccountMId">
                    <option value="0">请选择</option>
                    <?php foreach($masterAccountData as $item){?>
                    <option value="<?php echo $item["id"]?>" <?php if(intval($data[0]["myMasterAccountMId"])==intval($item["id"])){?>selected="selected"<?php } ?>><?php echo $item["userName"]."/".$item["trueName"];?></option>
                    <?php }?>
                </select>
            </td>
        </tr>
    </table>
    <table class="qxTable" id="mytable" style="width: 100%"  cellspacing="0">
        <tr scope="col">
            <td align="center">
                <input type="button" onclick="return updateInfo();" value="更新"/>
            </td>
        </tr>
    </table>
    <script>
        function updateInfo()
        {
            var qstr = "";
            var IsMasterAccount = 0;
            if($("#IsMasterAccount").is(':checked'))
            {
                IsMasterAccount = 1;
            }
            qstr = "IsMasterAccount="+IsMasterAccount;
            var stoneAddtion = $("#stoneAddtion").val();
            var modelAddtion = $("#modelAddtion").val();
            var myMasterAccountMId = $("#myMasterAccountMId").val();

            if(!isNumber(stoneAddtion))
            {
                alert('石头加点请填写数值');
                return false;
            }
            qstr = qstr+'&stoneAddtion='+stoneAddtion;
            if(!isNumber(modelAddtion))
            {
                alert('款号加点请填写数值');
                return false;
            }
            qstr = qstr+'&modelAddtion='+modelAddtion;
            if(!isNumber(myMasterAccountMId))
            {
                alert('请选择正确的所属主账号');
                return false;
            }
            qstr = qstr+'&myMasterAccountMId='+myMasterAccountMId+'&uid=<?php echo $userid;?>';
            jNotify("处理中请稍后...");
            $.get("/admin/AdminApi/modifyMemberInfoDo?"+qstr,{},function(data)
                {
                    closejNotify();
                    CheckObjErrorAndTure(data);
                    $("#modelAddtiono").html(data.data.modelAddtion);
                    $("#stoneAddtiono").html(data.data.stoneAddtion);
                }
            ,'json');
        }
    </script>
</block>