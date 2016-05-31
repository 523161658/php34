<?php

namespace Admin\Controller;

class AdminController extends PlateformController {

    public function add() {
        if (IS_POST) {
            $model = D('Admin/Admin');
            if ($model->create(I('post.'), 1)) {
                if ($id = $model->add()) {
                    $this->success('添加成功！', U('lst?p=' . I('get.p')));
                    exit;
                }
            }
            $this->error($model->getError());
        }

        $role_model = D('role');
        $role = $role_model->select();
        $this->assign('role', $role);

        $this->setPageInfo('添加管理员', '管理员列表', U('lst?p=' . I('get.p')));
        $this->display();
    }

    public function edit() {
        $id = I('get.id');
        $admin_id = session('user.id');
        if($admin_id > 1 && $admin_id != $id){
            $this->error("您没有权限更改！");
        }
        if (IS_POST) {
            $model = D('Admin/Admin');
            if ($model->create(I('post.'), 2)) {
                if ($model->save() !== FALSE) {
                    $this->success('修改成功！', U('lst', array('p' => I('get.p', 1))));
                    exit;
                }
            }
            $this->error($model->getError());
        }
        $model = M('Admin');
        $data = $model->field('a.*,GROUP_CONCAT(b.role_id) role_id')->alias('a')->join('left join php34_admin_role b on a.id=b.admin_id')->where(array('a.id' => array('eq',$id)))->group('a.id')->find();
        $this->assign('data', $data);

        $role_model = D('role');
        $role = $role_model->select();
        $this->assign('role', $role);

        $this->setPageInfo('修改管理员', '管理员列表', U('lst?p=' . I('get.p')));
        $this->display();
    }

    public function delete() {
        $model = D('Admin/Admin');
        if ($model->delete(I('get.id', 0)) !== FALSE) {
            $this->success('删除成功！', U('lst', array('p' => I('get.p', 1))));
            exit;
        } else {
            $this->error($model->getError());
        }
    }

    public function lst() {
        $model = D('Admin/Admin');
        $data = $model->search();

        $this->assign(array(
            'data' => $data['data'],
            'page' => $data['page'],
        ));

        $this->setPageInfo('管理员列表', '添加管理员', U('add'));
        $this->display();
    }
    
    public function updateIsuse(){
        $id = I('get.id');
        $admin_model = M('admin');
        $info = $admin_model->find($id);
        if($info['is_use'] == 1){
            $admin_model->where(array('id' => array('eq', $id)))->setField('is_use','0');
            echo 0;
        }else{
            $admin_model->where(array('id' => array('eq', $id)))->setField('is_use','1');
            echo 1;
        }
    }

}
