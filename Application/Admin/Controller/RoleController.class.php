<?php

namespace Admin\Controller;

class RoleController extends PlateformController {

    public function add() {
        if (IS_POST) {
            $model = D('Admin/Role');
            if ($model->create(I('post.'), 1)) {
                if ($id = $model->add()) {
                    $this->success('添加成功！', U('lst?p=' . I('get.p')));
                    exit;
                }
            }
            $this->error($model->getError());
        }

        $privilege_model = D('privilege');
        $privilege = $privilege_model->getTree();
        $this->assign('privilege', $privilege);
        $this->setPageInfo('添加角色', '角色列表', U('lst?p=' . I('get.p')));
        $this->display();
    }

    public function edit() {
        $id = I('get.id');
        if (IS_POST) {
            $model = D('Admin/Role');
            if ($model->create(I('post.'), 2)) {
                if ($model->save() !== FALSE) {
                    $this->success('修改成功！', U('lst', array('p' => I('get.p', 1))));
                    exit;
                }
            }
            $this->error($model->getError());
        }
        # 获取角色
        $model = M('Role');
        $data = $model->find($id);
        $this->assign('data', $data);

        # 获取所有权限
        $privilege_model = D('privilege');
        $privilege = $privilege_model->getTree();
        $this->assign('privilege', $privilege);
        
        # 获取已选中的权限
        $role_privilege_model = M('role_privilege');
        $selected_ids = $role_privilege_model->field('GROUP_CONCAT(pri_id) pri_ids')->where(array('role_id' => array('eq',$id)))->find();
        $this->assign('selected_ids', implode(',', $selected_ids));
        
        $this->setPageInfo('修改角色', '角色列表', U('lst?p=' . I('get.p')));
        $this->display();
    }

    public function delete() {
        $model = D('Admin/Role');
        if ($model->delete(I('get.id', 0)) !== FALSE) {
            $this->success('删除成功！', U('lst', array('p' => I('get.p', 1))));
            exit;
        } else {
            $this->error($model->getError());
        }
    }

    public function lst() {
        $model = D('Admin/Role');
        $data = $model->search();

        $this->assign(array(
            'data' => $data['data'],
            'page' => $data['page'],
        ));

        $this->setPageInfo('角色列表', '添加角色', U('add'));
        $this->display();
    }

}
