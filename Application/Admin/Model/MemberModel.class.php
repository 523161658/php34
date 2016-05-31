<?php

namespace Admin\Model;

use Think\Model;

class MemberModel extends Model {

    protected $insertFields = array('email', 'password', 'cpassword', 'face', 'addtime', 'email_code', 'checkcode');
    protected $updateFields = array('id', 'email', 'password', 'cpassword', 'face', 'addtime', 'email_code');
    protected $_validate = array(
        array('email', 'require', '邮箱名称不能为空！', 1, 'regex', 3),
        array('email', 'email', '邮箱名称不符合格式！', 1, 'regex', 3),
        array('email', '6,30', '邮箱名称长度为6-30位！', 1, 'length', 3),
        array('email', '', '该邮箱已被注册，请换其他邮箱！', 1, 'unique', 3),
        array('password', 'require', '密码名称不能为空！', 1, 'regex', 3),
        array('password', '6,15', '密码长度为6-15位！', 1, 'length', 3),
        array('cpassword', 'password', '密码不一致！', 1, 'confirm', 3),
        array('checkcode', 'checkCaptcha', '验证码不正确！', 1, 'callback', 3),
    );
    public $login_validate = array(
        array('email', 'require', '邮箱名称不能为空！', 1, 'regex'),
        array('email', 'email', '邮箱名称不符合格式！', 1, 'regex'),
        array('email', '6,30', '邮箱名称长度为6-30位！', 1, 'length'),
        array('password', 'require', '密码名称不能为空！', 1, 'regex'),
        array('password', '6,15', '密码长度为6-15位！', 1, 'length'),
        array('checkcode', 'checkCaptcha', '验证码不正确！', 1, 'callback'),
    );
    protected $_auto = array(
        array('addtime', 'time', 1, 'function'), // 对addtime字段在添加的时候写入当前时间戳
        array('email_code', 'getEmailKey', 1, 'callback'), // 对addtime字段在添加的时候写入当前时间戳
    );

    /**
     * 验证码校验
     */
    public function checkCaptcha($code) {
        $verify = new \Think\Verify();
        return $verify->check($code);
    }

    /*
     * 生成邮箱密钥
     */

    public function getEmailKey() {
        return MD5(uniqid());
    }
    /**
     * 添加前
     * @param array $data
     * @param type $option
     */
    protected function _before_insert(&$data, $option) {
        $data['password'] = MD5($data['password'] . C('MD5_PREFIX'));
    }

    /**
     * 插入后操作
     */
    public function _after_insert($data, $options) {
        // 自动发送邮件
        $mailto = $data['email'];
        $name = '';
        $title = 'PHP34邮箱验证';
        $content = <<<HTML
                <P>请点击下面的地址，或将下面的地址复制到浏览器的地址栏中，进行邮箱验证！</P>
                <p><a href="http://www.php34.com/home/member/checkEmail/code/{$data['email_code']}" >http://www.php34.com/home/member/checkEmail/code/{$data['email_code']}</a></p>
HTML;
        $result = sendMail($mailto, $name, $title, $content);
    }

    /**
     * 验证是否登陆
     */
    public function checkLogin() {
        # 获取用户名及密码
        $email = $this->email;
        $password = $this->password;
        $member = $this->where(array('email' => array('eq', $email)))->find();
        if ($member) {
            # 判断账户是否被验证
            if (empty($member['email_code'])) {
                if ($member['password'] == MD5($password . C('MD5_PREFIX'))) {
                    session('member', $member);
                    return true;
                } else {
                    $this->error = '密码不正确！';
                    return false;
                }
            } else {
                $this->error = '该账户未被验证！';
                return false;
            }
        } else {
            $this->error = '用户名不存在！';
            return false;
        }
    }

}
