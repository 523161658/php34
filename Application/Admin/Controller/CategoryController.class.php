<?php

namespace Admin\Controller;

class CategoryController extends PlateformController {

    public function add() {
        if (IS_POST) {
            $model = D('Admin/Category');
            if ($model->create(I('post.'), 1)) {
                if ($id = $model->add()) {
                    $this->success('添加成功！', U('lst?p=' . I('get.p')));
                    exit;
                }
            }
            $this->error($model->getError());
        }
        $parentModel = D('Admin/Category');
        $parentData = $parentModel->getTree();
        $this->assign('parentData', $parentData);
        # 获取商品类型数据
        $typeModel = M('Type');
        $typeData = $typeModel->select();
        $this->assign('typeData', $typeData);

        $this->setPageInfo('添加商品分类', '商品分类列表', U('lst?p=' . I('get.p')));
        $this->display();
    }

    public function edit() {
        $id = I('get.id');
        if (IS_POST) {
            $model = D('Admin/Category');
            if ($model->create(I('post.'), 2)) {
                if ($model->save() !== FALSE) {
                    $this->success('修改成功！', U('lst', array('p' => I('get.p', 1))));
                    exit;
                }
            }
            $this->error($model->getError());
        }
        $model = M('Category');
        $data = $model->find($id);
        
        $parentModel = D('Admin/Category');
        $parentData = $parentModel->getTree();
        $children = $parentModel->getChildren($id);

        # 获取商品搜索类型数据
        $typeModel = M('Type');
        $typeData = $typeModel->select();

        # 获取商品搜索属性数据
        $attributModel = M('Attribute');
        $attrData = $attributModel->where(array('id'=>array('in',$data['search_attr_id'])))->select();
        if($attrData == null){
            $attrData = array(0);
        }
        
        $this->assign(array(
            'data'  =>  $data,
            'parentData' => $parentData,
            'children' => $children,
            'typeData' => $typeData,
            'attrData' => $attrData,
        ));

        $this->setPageInfo('修改商品分类', '商品分类列表', U('lst?p=' . I('get.p')));
        $this->display();
    }

    public function delete() {
        $model = D('Admin/Category');
        if ($model->delete(I('get.id', 0)) !== FALSE) {
            $this->success('删除成功！', U('lst', array('p' => I('get.p', 1))));
            exit;
        } else {
            $this->error($model->getError());
        }
    }

    public function lst() {
        $model = D('Admin/Category');
        $data = $model->getTree();
        $this->assign(array(
            'data' => $data,
        ));

        $this->setPageInfo('商品分类列表', '添加商品分类', U('add'));
        $this->display();
    }

    /**
     * 根据类型ID获取属性列表
     */
    public function getAttr() {
        $typeId = I('get.typeId');

        $model = M('Attribute');
        $data = $model->field('id,attr_name')->where(array('type_id' => array('eq', $typeId)))->select();
        $this->ajaxReturn($data);
    }

}
