<?php

/**
 * html代码防攻击
 */
function removeXXS($dirty_html) {
    static $purifier = null;
    if ($purifier === null) {
        require_once("./Public/Htmlpurifier/HTMLPurifier.auto.php");
        $config = HTMLPurifier_Config::createDefault();
        $config->set('HTML.TargetBlank', TRUE);
        $purifier = new HTMLPurifier($config);
    }
    return $purifier->purify($dirty_html);
}

/**
 * 上传图片
 * @param type $imgName 上传图片的名称
 * @param type $dirName 上传图片路径
 * @param type $thumb   形成缩略图的大小参数
 * @return string
 */
function uploadOne($imgName, $dirName, $thumb = array()) {
    // 上传LOGO
    if (isset($_FILES[$imgName]) && $_FILES[$imgName]['error'] == 0) {
        $rootPath = C('UPLOADS_ROOT_PATH');
        $upload = new \Think\Upload(array(
            'rootPath' => $rootPath,
        )); // 实例化上传类
        $upload->maxSize = (int) C('maxSize') * 1024 * 1024; // 设置附件上传大小
        $upload->exts = C('exts'); // 设置附件上传类型
        $upload->savePath = $dirName . '/'; // 图片二级目录的名称
        // 上传文件 
        $info = $upload->uploadOne($_FILES[$imgName]);
        if (!$info) {
            return array(
                'ok' => 0,
                'error' => $upload->getError(),
            );
        } else {
            $ret['ok'] = 1;
            $ret['images'][0] = $filePath = $info['savepath'] . $info['savename'];
            // 判断是否生成缩略图
            if ($thumb) {
                $image = new \Think\Image();
                // 循环生成缩略图
                foreach ($thumb as $k => $v) {
                    $ret['images'][$k + 1] = $info['savepath'] . 'thumb_' . $k . '_' . $info['savename'];
                    // 打开要处理的图片
                    $image->open($rootPath . $filePath);
                    $image->thumb($v[0], $v[1])->save($rootPath . $ret['images'][$k + 1]);
                }
            }
            return $ret;
        }
    }
}

/**
 * 显示图片
 * @param string $url
 * @param type $width
 * @param type $height
 */
function showImage($url = '', $width = '', $height = '') {
    if ($width) {
        $width = "width='$width'";
    }
    if ($height) {
        $height = "height='$height'";
    }
    if ($url) {
        $url = '/Public/Uploads/' . $url;
        echo "<img src='$url' $width $width />";
    }
}

/**
 * 删除图片：参数：一维数组：所有要删除的图片的路径
 * @param type $images
 */
function deleteImage($images) {
    // 先取出图片所在目录
    $rp = C('UPLOADS_ROOT_PATH');
    foreach ($images as $v) {
        // @错误抵制符：忽略掉错误,一般在删除文件时都添加上这个
        @unlink($rp . $v);
    }
}

/*
 * 发送邮件
 */

function sendMail($mailto, $name, $title, $content) {
    require 'Public/PHPMailer/PHPMailerAutoload.php';

    $mail = new PHPMailer;

    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 25;                                    // TCP port to connect to
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->CharSet = 'UTF-8';

    $mail->From = C('MAIL_FROM');
    $mail->FromName = C('MAIL_FROM_NAME');
    $mail->Host = C('MAIL_HOST');
    $mail->Username = C('MAIL_USERNAME');
    $mail->Password = C('MAIL_PASSWORD');

    $mail->AddAddress($mailto, $name);     // Add a recipient
    $mail->Subject = $title;
    $mail->Body = $content;

    if (!$mail->send()) {
        return $mail->ErrorInfo;
    } else {
        return 'Message has been sent';
    }
}
