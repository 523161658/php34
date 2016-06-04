<?php

namespace Home\Controller;

class MemberController extends PlateformController {
    /*
     * 注册会员
     */

    public function regist() {
        $member_model = D('Admin/Member');
        if (IS_POST) {
            if ($member_model->create()) {
                if ($member_model->add()) {
                    $this->success('注册成功！请登录邮箱完成注册',U('login'));
                    exit;
                }
            } else {
                $this->error($member_model->getError());
            }
        }
        $this->setPageInfo('会员注册', '会员注册', '会员注册', 1, array('login'));
        $this->display();
    }

    /**
     * 验证码生成控制器
     */
    public function CAPTCHA() {
        $config = array(
            fontSize => 14,
            length => 4,
            imageW => 150,
            imageH => 36,
            useNoise => false,
            useCurve => false,
        );
        $Verify = new \Think\Verify($config);
        $Verify->entry();
    }
    
    /**
     * 验证邮箱
     */
    public function checkEmail(){
        $email_code = I('get.code');
        $member_model = M('Member');
        $member = $member_model->where(array('email_code'=>array('eq',$email_code)))->find();
        if(!empty($member)){
            $result = $member_model->where(array('email'=>array('eq',$member['email'])))->setField('email_code','');
            if($result>0){
                $this->success('验证成功！', U('login'));
            }else{
                $this->error("验证失败！");
            }
        }
    }
    
    /**
     * 会员登陆
     */
    public function login(){
        $member_model = D('Admin/Member');
        if (IS_POST) {
            if ($member_model->validate($member_model->login_validate)->create(I('post.'),7)) {
                if ($member_model->checkLogin()) {
                    if($from = session('from')){
                        session('from',null);
                        
                        // 将cookie数据入库
                        $cartModel = D('Admin/Cart');
                        $cartModel->cookieToDb();
                        
                        redirect($from);
                    }else{
                        redirect('/');
                    }
                    exit;
                }
            }
            $this->error($member_model->getError());
        }
        $this->setPageInfo('会员登陆', '会员登陆', '会员登陆', 1, array('login'));
        $this->display();        
    }
    
    /**
     * 退出
     */
    public function loginout(){
        session('member',null);
        redirect('/');
    }
    
    /**
     * 通过AJAX获取会员信息
     */
    public function getLoginInfo(){
        $member = session('member');
        $info = array();
        if(empty($member)){
            $info['is_login'] = '0';
        }else{
            $info['is_login'] = '1';
            $info['email'] = $member['email'];
        }
        $this->ajaxReturn($info);
    }

}
