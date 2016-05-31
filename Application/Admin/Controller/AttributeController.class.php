<?php

namespace Admin\Controller;

class AttributeController extends PlateformController {

    public function add() {
        if (IS_POST) {
            $model = D('Admin/Attribute');
            if ($model->create(I('post.'), 1)) {
                if ($id = $model->add()) {
                    $this->success('添加成功！', U('lst',array('p'=>I('get.p',1),'type_id'=>I('get.type_id'))));
                    exit;
                }
            }
            $this->error($model->getError());
        }

        $type_model = M('Type');
        $type = $type_model->select();
        $this->assign('type', $type);

        $this->setPageInfo('添加属性', '属性列表', U('lst',array('p'=>I('get.p',1),'type_id'=>I('get.type_id'))));
        $this->display();
    }

    public function edit() {
        $id = I('get.attr_id');
        if (IS_POST) {
            $model = D('Admin/Attribute');
            if ($model->create(I('post.'), 2)) {
                if ($model->save() !== FALSE) {
                    $this->success('修改成功！', U('lst', array('p'=>I('get.p',1),'type_id'=>I('get.type_id'))));
                    exit;
                }
            }
            $this->error($model->getError());
        }
        $model = M('Attribute');
        $data = $model->field('a.*')->alias('a')->join('left join php34_type b on  a.type_id = b.id')->where(array('a.id'=>array('eq',$id)))->find();
        $this->assign('data', $data);
        
        $type_model = M('Type');
        $type = $type_model->select();
        $this->assign('type',$type);

        $this->setPageInfo('修改属性', '属性列表', U('lst',array('type_id'=>$data['type_id'],'p'=>I('get.p',1))));
        $this->display();
    }

    public function delete() {
        $model = D('Admin/Attribute');
        if ($model->delete(I('get.attr_id', 0)) !== FALSE) {
            $this->success('删除成功！', U('lst', array('p'=>I('get.p',1),'type_id'=>I('get.type_id'))));
            exit;
        } else {
            $this->error($model->getError());
        }
    }

    public function lst() {
        $model = D('Admin/Attribute');
        $data = $model->search();

        $type_model = M('Type');
        $type = $type_model->select();
        

        $this->assign(array(
            'data' => $data['data'],
            'page' => $data['page'],
            'type' => $type,
        ));

        $this->setPageInfo('属性列表', '添加属性', U('add', array('type_id' => I('get.type_id'))));
        $this->display();
    }

}
