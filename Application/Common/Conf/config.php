<?php

return array(
//    //'配置项'=>'配置值'

    /* 数据库设置 */
    'DB_TYPE' => 'mysql', // 数据库类型
    'DB_HOST' => '127.0.0.1', // 服务器地址
    'DB_NAME' => 'php34', // 数据库名
    'DB_USER' => 'root', // 用户名
    'DB_PWD' => 'admin', // 密码
    'DB_PORT' => '3306', // 端口
    'DB_PREFIX' => 'php34_', // 数据库表前缀
//    'SHOW_PAGE_TRACE' =>true,

    /* 默认参数过滤方法 用于I函数... */
    'DEFAULT_FILTER' => 'trim,removeXXS',
    /* 图片上传参数 */
    'maxSize' => 2, // 最大上传图片大小，单位M
    'savePath' => 'Goods/', //图片保存位置
    'exts' => array('jpg', 'gif', 'png', 'jpeg'), //允许上传图片的格式
    'UPLOADS_ROOT_PATH' => './Public/Uploads/', //图片上传根目录

    /* MD5加密后缀 */
    'MD5_PREFIX' => 'qoebnvhx@3iqADhecW',
    
    /* 定义路由规则 */
    'URL_ROUTER_ON' => true,
    'URL_MAP_RULES' => array(
        '/admin' => '/index.php/admin/login/login',
        '/home' => '/index.php/home/index/index',
    ),
    
    'URL_MODEL'             =>  2,       // URL访问模式,可选参数0、1、2、3,代表以下四种模式：
);
