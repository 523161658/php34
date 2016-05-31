<?php

namespace Admin\Model;

use Think\Model;

class PrivilegeModel extends Model {

    protected $insertFields = array('pri_name', 'module_name', 'controller_name', 'action_name', 'parent_id');
    protected $updateFields = array('id', 'pri_name', 'module_name', 'controller_name', 'action_name', 'parent_id');
    protected $_validate = array(
        array('pri_name', '1,30', '权限名称的值最长不能超过 30 个字符！', 2, 'length', 3),
        array('module_name', '1,10', '模块名称的值最长不能超过 20 个字符！', 2, 'length', 3),
        array('controller_name', '1,10', '控制器名称的值最长不能超过 20 个字符！', 2, 'length', 3),
        array('action_name', '1,10', '方法名称的值最长不能超过 20 个字符！', 2, 'length', 3),
        array('parent_id', 'number', '上级权限的ID，0：代表顶级权限必须是一个整数！', 2, 'regex', 3),
    );


    /**
     * 获取权限嵌套结构数据
     */
    public function getNested(){
        $admin_id = session('user.id');
        if($admin_id == 1){
            $data = $this->select();
        }else{
            $sql = "select * from php34_admin_role a left join php34_role_privilege b on a.role_id = b.role_id left join  php34_privilege c on c.id = b.pri_id where a.admin_id =" . $admin_id;
            $data = $this->query($sql);
        }
        return $this->_reNested($data);
    }
    
    public function _reNested($data){
        $ret = array();
        foreach ($data as $v){
            if($v['parent_id'] == 0){
                foreach ($data as $k1 => $v1){
                    if($v1['parent_id'] == $v['id']){
                        $v['child'][]=$v1;
                    }
                }
                $ret[]=$v;
            }
        }
        return $ret;
    }
    /*     * *********************************** 递归相关方法 ************************************ */   
    public function getTree() {
        $tempdata = $this->select();
        $data = array();
        foreach($tempdata as $k => $v){
            $data[$k] = $v;
            # 获取上级权限的名称
            if($par_name = $this->field('pri_name')->where(array('id' => array('eq',$v['parent_id'])))->find()){
                $data[$k]['parent_name'] = $par_name['pri_name'];
            }else{
                $data[$k]['parent_name'] = '顶级权限';
            }
        }
        return $this->_reSort($data);
    }

    private function _reSort($data, $parent_id = 0, $level = 0, $isClear = TRUE) {
        static $ret = array();
        if ($isClear)
            $ret = array();
        foreach ($data as $k => $v) {
            if ($v['parent_id'] == $parent_id) {
                $v['level'] = $level;
                $ret[] = $v;
                $this->_reSort($data, $v['id'], $level + 1, FALSE);
            }
        }
        return $ret;
    }

    public function getChildren($id) {
        $data = $this->select();
        return $this->_children($data, $id);
    }

    private function _children($data, $parent_id = 0, $isClear = TRUE) {
        static $ret = array();
        if ($isClear)
            $ret = array();
        foreach ($data as $k => $v) {
            if ($v['parent_id'] == $parent_id) {
                $ret[] = $v['id'];
                $this->_children($data, $v['id'], FALSE);
            }
        }
        return $ret;
    }

    /*     * ********************************** 其他方法 ******************************************* */

    public function _before_delete($option) {
        // 先找出所有的子分类
        $children = $this->getChildren($option['where']['id']);
        // 如果有子分类都删除掉
        if ($children) {
            $children = implode(',', $children);
            $this->execute("DELETE FROM php34_privilege WHERE id IN($children)");
        }
    }

}
