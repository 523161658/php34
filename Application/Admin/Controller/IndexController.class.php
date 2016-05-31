<?php

namespace Admin\Controller;

/**
 * 后台首页
 */
class IndexController extends PlateformController {
    public function index(){
        $this->display();
    }
    public function top(){
        $this->display();
    }
    public function menu(){
        # 获取菜单（四维数组）
        $privilege_model = D('privilege');
        $pri_nested = $privilege_model->getNested();

        $this->assign('pri_nested', $pri_nested);
        $this->display();
    }
    public function main(){
        $this->display();
    }
}
