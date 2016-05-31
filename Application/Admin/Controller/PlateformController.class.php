<?php

namespace Admin\Controller;

use Think\Controller;

/**
 * 平台控制器
 */
class PlateformController extends Controller {

    /**
     * 构造函数
     */
    public function __construct() {
        parent::__construct();
        
        # 验证是否已登陆
        $this->isLogin();
        # 验证是否有权限进入该模块
        $this->isPrivilege();
    }

    /**
     * 验证是否已登陆
     */
    public function isLogin() {
        if (!session('user')) {
            $this->redirect('login/login');
            exit();
        }
    }

    /**
     * 验证是否有权限进入该模块
     */
    public function isPrivilege() {
        # 获取管理员的id
        $admin_id = session('user.id');

        # 任何人只要登陆了，就能进入后台主页面  如果是超级管理员就能访问
        if (CONTROLLER_NAME === 'Index' || $admin_id == 1) {
            return true;
        } else {
            # 否则判断是否有权限
            $sql = "select count(*) count from php34_privilege a left join php34_role_privilege b on a.id=b.pri_id left join php34_admin_role c on c.role_id = b.role_id where admin_id=" . $admin_id . " and module_name='" . MODULE_NAME . "' and controller_name='" . CONTROLLER_NAME . "' and action_name= '" . ACTION_NAME . "'";
            $model = M();
            $pri = $model->query($sql);
            if ($pri[0]['count'] < 1) {
                $this->error('您没有权限访问！');
            }
        }
    }

    /**
     * 设置布局文件头信息方法
     */
    public function setPageInfo($title, $btn_name, $link) {
        $this->assign(array(
            'title' => $title,
            'btn_name' => $btn_name,
            'link' => $link,
        ));
    }

}
