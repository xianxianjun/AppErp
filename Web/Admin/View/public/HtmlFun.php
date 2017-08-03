<?php
function PageTabsHtml($pageTabs,$pageQrt,$cpage,$pageCount,$reCount)
{
?>
    <div style="width: 100%; text-align: center">
        <?php if($cpage <= 1){?>
            &nbsp;<a href="javascript:return false;">第一页</a>
            &nbsp;<a href="javascript:return false;">前一页</a>
        <?php }else{?>
            &nbsp;<a style="text-decoration:underline" href="first<?php echo $pageTabs . $pageQrt;?>">第一页</a>
            &nbsp;<a style="text-decoration:underline" href="per<?php echo $pageTabs . $pageQrt;?>">前一页</a>
        <?php }?>
        <?php if($cpage == $pageCount){?>
            &nbsp;<a href="javascript:return false;">下一页</a>
            &nbsp;<a href="javascript:return false;">最后一页</a>
        <?php }else{?>
            &nbsp;<a style="text-decoration:underline" href="next<?php echo $pageTabs . $pageQrt;?>">下一页</a>
            &nbsp;<a style="text-decoration:underline" href="last<?php echo $pageTabs . $pageQrt;?>">最后一页</a>
        <?php }?>
        &nbsp;当前是<?php echo $cpage;?>页
        &nbsp;总页数<?php echo $pageCount;?>页
        &nbsp;记录总数<?php echo $reCount;?>
    </div>
<?php
}
?>