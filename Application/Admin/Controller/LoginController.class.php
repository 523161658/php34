<?php
namespace Admin\Controller;
use Think\Controller;
/**
 * 登陆控制器
 */
class LoginController extends Controller {
    /**
     * 登陆控制器
     */
    public function login(){
        # 如果表单不为空，则进行验证
        if(IS_POST){
            $admin_model = D('admin');
            if($admin_model->validate($admin_model->check_validate)->create(I('post.'),7)){
                if($admin_model->checkLogin()) {
                    $this->redirect('admin/index/index');
                    exit();
                }
            }
            $this->error($admin_model->getError());
        }

        # 显示表单
        $this->display();
    }
    
    /**
     * 退出
     */
    public function loginout(){
        session(null);
        $this->redirect('admin/login/login');
    }


    /**
     * 验证码生成控制器
     */
    public function CAPTCHA(){
        $config = array(
            fontSize    =>  14,
            length      =>  4,
            imageW      =>  120,
            imageH      =>  30,
            useNoise    =>  false,
            useCurve    =>  false,
        );
        $Verify = new \Think\Verify($config);
        $Verify->entry();
    }
}

