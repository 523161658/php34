<?php

return array(
    // 配置前台静态
    'HTML_CACHE_ON' => false, // 开启静态缓存
    'HTML_CACHE_TIME' => 60, // 全局静态缓存有效期（秒）
    'HTML_FILE_SUFFIX' => '.html', // 设置静态缓存文件后缀
    'HTML_CACHE_RULES' => array(// 定义静态缓存规则
        // 定义格式1 数组方式
        'index' => array('index', '3600'),
        'goods:detail' => array('{:controller}_{id}', '3600'),
    ),
    // 配置邮箱
    'MAIL_FROM' => '523161658@qq.com', // 发件人email
    'MAIL_FROM_NAME' => 'php34', // 发件人姓名
    'MAIL_HOST' => 'smtp.qq.com', // 邮件服务器的地址
    'MAIL_USERNAME' => '523161658',
    'MAIL_PASSWORD' => 'djc33052312321...',
    
    // 默认头像
    'DEFAULT_FACE' =>   'default_face.png',
);
