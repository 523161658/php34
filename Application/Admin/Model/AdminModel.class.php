<?php

namespace Admin\Model;

use Think\Model;

class AdminModel extends Model {

    protected $insertFields = array('username', 'password', 'cpassword', 'role_id', 'is_use');
    protected $updateFields = array('id', 'username', 'password', 'cpassword', 'role_id', 'is_use');
    protected $_validate = array(
        array('username', 'require', '账号不能为空！', 1, 'regex', 3),
        array('username', '1,30', '账号的值最长不能超过 30 个字符！', 1, 'length', 3),
        array('username', '', '账号已存在，请更换账号', 1, 'unique', 3),
        array('password', 'require', '密码不能为空！', 1, 'regex', 1),
        array('cpassword', 'password', '两次输入的密码不一致，请重新输入！', 1, 'confirm', 3),
        array('is_use', 'number', '是否启用 1：启用0：禁用必须是一个整数！', 1, 'regex', 3),
    );

    public function search($pageSize = 20) {
        /*         * ************************************** 搜索 *************************************** */
        $where = array();
        /*         * *********************************** 翻页 *************************************** */
        $count = $this->alias('a')->where($where)->count();
        $page = new \Think\Page($count, $pageSize);
        // 配置翻页的样式
        $page->setConfig('prev', '上一页');
        $page->setConfig('next', '下一页');
        $data['page'] = $page->show();
        /*         * ************************************ 取数据 ***************************************** */
        $data['data'] = $this->field('a.*,GROUP_CONCAT(c.role_name) role_name')->alias('a')->join('left join php34_admin_role b on a.id=b.admin_id left join php34_role c on b.role_id=c.id')->where($where)->group('a.id')->limit($page->firstRow . ',' . $page->listRows)->select();
        return $data;
    }

    // 添加前
    protected function _before_insert(&$data, $option) {
        $data['password'] = MD5($data['password'] . C('MD5_PREFIX'));
    }

    // 添加后
    protected function _after_insert($data, $options) {
        $admin_role_model = M('admin_role');
        $role_ids = I('post.role_id');
        if ($role_ids) {
            foreach ($role_ids as $k => $v) {
                $admin_role_model->add(array(
                    'admin_id' => $data['id'],
                    'role_id' => $v,
                ));
            }
        }
    }

    // 修改前
    protected function _before_update(&$data, $option) {
        # 判断是否为超级管理员，如是，则无法禁用
        if($option['where']['id'] == 1 && $data['is_use'] == 0){
            $this->error = "该账户为超级管理员，不允许禁用!";
            return false;            
        }
        
        if (!empty($data['password'])) {
            $data['password'] = MD5($data['password'] . C('MD5_PREFIX'));
        } else {
            unset($data['password']);
        }
    }
    
    // 修改后
    protected function _after_update($data, $options) {
        # 先清空管理员拥有的角色数据
        $admin_role_model = M('admin_role');
        $admin_role_model->where(array('admin_id' => array('eq',$options['where']['id'])))->delete();
        
        # 添加新的角色
        $role_ids = I('post.role_id');
        if ($role_ids) {
            foreach ($role_ids as $k => $v) {
                $admin_role_model->add(array(
                    'admin_id' => $options['where']['id'],
                    'role_id' => $v,
                ));
            }
        }
    }

    // 删除前
    protected function _before_delete($option) {
        # 判断是否为超级管理员，如是则不允许删除
        if($option['where']['id'] == 1){
            $this->error = "该账户为超级管理员，不允许删除!";
            return false;
        }
        # 先将管理员与角色关联表中的对应数据删除
        $admin_role_model = M('admin_role');
        $admin_role_model->where(array('admin_id' => array('eq', $option['where']['id'])))->delete();
    }

    /**
     * 设置数据校验规则
     */
    public $check_validate = array(
        array('username', 'require', '用户名不能为空！', 1),
        array('password', 'require', '密码不能为空！', 1),
        array('captcha', 'require', '验证码不能为空！', 1),
        array('captcha', 'checkCaptcha', '验证码不正确！', 1, 'callback'),
    );

    /**
     * 验证用户名/密码是否正确
     * 正确返回true
     * 错误返回false
     */
    public function checkLogin() {
        # 获取用户名及密码
        $username = $this->username;
        $password = $this->password;
        # 判断用户名是否存在
        $condition = array(
            'username' => array('eq', $username),
        );
        $user = $this->where($condition)->find();
        if ($user) {
            # 判断账户是否被禁用
            if ($user['id'] == 1 || $user['is_use'] == 1) {
                if ($user['password'] == MD5($password . C('MD5_PREFIX'))) {
                    session('user', $user);
                    return true;
                } else {
                    $this->error = '密码不正确！';
                    return false;
                }
            } else {
                $this->error = '该账户已被禁用！';
                return false;
            }
        } else {
            $this->error = '用户名不存在！';
            return false;
        }
    }

    /**
     * 验证码校验
     */
    public function checkCaptcha($code) {
        $verify = new \Think\Verify();
        return $verify->check($code);
    }

}
