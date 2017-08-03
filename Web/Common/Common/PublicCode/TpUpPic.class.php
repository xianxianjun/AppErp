<?php
namespace Common\Common\PublicCode;
class TpUpPic
{
    public static function UploadPic($maxSzie = 3000000, $err = '')
    {
        $upload = new \Think\Upload();// 实例化上传类
        $upload->savePath = 'Pics/';// 设置附件上传目录
        $upload->maxSize = $maxSzie;// 设置附件上传大小
        $upload->allowExts = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->allowTypes = array('image/jpg', 'image/jpeg', 'image/pjpeg', 'image/png', 'image/gif');
        $info = $upload->upload();
        if (!$info) $err = $upload->getError();
        return $info;
    }

    public static function UploadPicRePicPath($maxSzie = 3000000, $err = '')
    {
        $PicPathName = '';
        $info = TpUpPic::UploadPic($maxSzie, $err);
        foreach ($info as $v) {
            $PicPathName = $v['savepath'] . $v['savename'];
        }
        if ($PicPathName != '') $PicPathName = 'Uploads/' . $PicPathName;
        return $PicPathName;
    }

    public static function UploadPicRePicPathThumbs($maxSzie = 3000000, $err = '', $scWidth = 150, $deviation = 0)
    {
        $PicPathName = '';
        $info = TpUpPic::UploadPic($maxSzie, $err);
        $imagepath = '';
        if ($info) {
        foreach ($info as $v) {
            $imagepath = $v['savepath'] . $v['savename'];
            $image = "./Uploads/" . $imagepath;
            $PicPathName = TpUpPic::thumbs($image, $v['savepath'] . 's_' . $v['savename'], $scWidth, $deviation);
        }
    }

        if ($PicPathName != '')
            $PicPathName = 'Uploads/' . $PicPathName;
        else
            $PicPathName = 'Uploads/' . $imagepath;
        return $PicPathName;
    }

    public static function MultiUploadPicRePicPathThumbs($maxSzie = 3000000, $err = '', $scWidth = 150, $deviation = 0)
    {
        $ArrPicPathName = null;
        $info = TpUpPic::UploadPic($maxSzie, $err);
        if ($info) {
            $n = 0;
            foreach ($info as $v) {
                $imagepath = $v['savepath'] . $v['savename'];
                $image = "./Uploads/" . $imagepath;
                $PicPathName = TpUpPic::thumbs($image, $v['savepath'] . 's_' . $v['savename'], $scWidth, $deviation);
                if ($PicPathName != '') {
                    $PicPathName = 'Uploads/' . $PicPathName;
                } else {
                    $PicPathName = 'Uploads/' . $imagepath;
                }
                $ArrPicPathName[$n] = $PicPathName;
                $n = $n + 1;
            }
        }
        return $ArrPicPathName;
    }

    /* ---------- 编辑图片 ------------ *
* $image	 原有图片
* $spath	 修改后的编辑图片
* $height 高度
* $width 宽度
* $thumbname 缩略名
*/
    public static function thumbs($imagepath, $spath, $scWidth = 150, $deviation = 0)
    { //传入图片
        $image = new \Think\Image();
        $image->open($imagepath);
        $width = $image->width(); // 返回图片的宽度
        $height = $image->height();
        if ($width + $deviation > $scWidth) {
            $width = $width / $scWidth; //取得图片的长宽比
            $height = ceil($height / $width);
            $image->thumb($scWidth, $height)->save('./Uploads/' . $spath);
            return $spath;//时间戳加后缀
        } else {
            return '';//'./Uploads/'
        }
    }
}