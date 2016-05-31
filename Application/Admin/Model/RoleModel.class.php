<?php

namespace Admin\Model;

use Think\Model;

class RoleModel extends Model {

    protected $insertFields = array('role_name');
    protected $updateFields = array('id', 'role_name');
    protected $_validate = array(
        array('role_name', 'require', '角色名称不能为空'),
        array('role_name', '1,30', '角色名称的值最长不能超过 30 个字符！', 2, 'length', 3),
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
        /* select a.*,GROUP_CONCAT(c.pri_name) pri_name from php34_role a left join php34_role_privilege b on a.id=b.role_id left join php34_privilege c on c.id = b.pri_id; */
        $data['data'] = $this->field('a.*,GROUP_CONCAT(c.pri_name) pri_name')->alias('a')->join('left join php34_role_privilege b on a.id=b.role_id left join php34_privilege c on c.id = b.pri_id')->where($where)->group('a.id')->limit($page->firstRow . ',' . $page->listRows)->select();
        return $data;
    }

    // 添加前
    protected function _before_insert(&$data, $option) {
        
    }

    // 修改前
    protected function _before_update(&$data, $option) {
        # 先清除原来的权限
        $role_privilege_model = M('role_privilege');
        $role_privilege_model->where(array('role_id'=>array('eq',$option['where']['id'])))->delete();
        
        # 获取权限列表
        $pri_ids = I('post.pri_id');
        if (isset($pri_ids)) {
            foreach ($pri_ids as $v) {
                $role_privilege_model->add(array(
                    'pri_id' => $v,
                    'role_id' => $option['where']['id'],
                ));
            }
        }
    }

    // 添加后
    protected function _after_insert($data, $options) {
        $pri_ids = I('post.pri_id');
        $role_privilege_model = M('role_privilege');
        if (isset($pri_ids)) {
            foreach ($pri_ids as $v) {
                $role_privilege_model->add(array(
                    'pri_id' => $v,
                    'role_id' => $data['id'],
                ));
            }
        }
    }

    // 删除前
    protected function _before_delete($option) {
        # 如果有管理员拥有该角色，则该角色不允许删除
        # array(3) { ["where"]=> array(1) { ["id"]=> int(1) } ["table"]=> string(10) "php34_role" ["model"]=> string(4) "Role" }
        $role_model = M('admin_role');
        $count = $role_model->where(array('role_id' => array('eq', $option['where']['id'])))->count();
        if ($count > 0) {
            $this->error = '存在管理员拥有该角色，无法删除！';
            return false;
        }
        # 如果没有管理员拥有该角色，则该角色允许删除，先删除该角色对应的权限
        $role_privilege = M('role_privilege');
        $role_privilege->where(array('role_id' => array('eq', $option['where']['id'])))->delete();
    }

    /*     * ********************************** 其他方法 ******************************************* */
}
