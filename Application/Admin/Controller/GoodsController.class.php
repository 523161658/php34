<?php

namespace Admin\Controller;

class GoodsController extends PlateformController {

    public function add() {
        if (IS_POST) {
            $model = D('Admin/Goods');
            if ($model->create(I('post.'), 1)) {
                if ($id = $model->add()) {
                    $this->success('添加成功！', U('lst?p=' . I('get.p')));
                    exit;
                }
            }
            $this->error($model->getError());
        }

        # 获取商品分类
        $category_model = D('Category');
        $cat_list = $category_model->getTree();
        $this->assign('cat_list', $cat_list);

        # 获取品牌数据
        $brand_model = M('Brand');
        $brand_list = $brand_model->select();
        $this->assign('brand_list', $brand_list);

        # 获取商品类型
        $type_model = M('Type');
        $type_list = $type_model->select();
        $this->assign('type_list', $type_list);

        # 获取会员级别
        $ml_model = M('MemberLevel');
        $ml_list = $ml_model->select();
        $this->assign('ml_list', $ml_list);

        $this->setPageInfo('添加商品', '商品列表', U('lst?p=' . I('get.p')));
        $this->display();
    }

    public function edit() {
        $id = I('get.id');
        if (IS_POST) {
            $model = D('Admin/Goods');
            if ($model->create(I('post.'), 2)) {
                if ($model->save() !== FALSE) {
                    $this->success('修改成功！', U('lst', array('p' => I('get.p', 1))));
                    exit;
                }
            }
            $this->error($model->getError());
        }
        $model = M('Goods');
        $data = $model->find($id);
        $this->assign('data', $data);

        # 获取商品分类
        $category_model = D('Category');
        $cat_list = $category_model->getTree();
        $this->assign('cat_list', $cat_list);

        # 获取扩展分类数据
        $ext_cat_model = M('GoodsCat');
        $ext_cats = $ext_cat_model->field('a.*,b.*')->alias('a')->join('left JOIN php34_category b on a.cat_id = b.id')->where(array('goods_id' => array('eq', $id)))->select();
        if (count($ext_cats) == 0) {
            $ext_cats[] = array(
                'goods_id' => '',
                'cat_id' => '',
                'id' => '',
                'cat_name' => '',
                'parent_id' => '',
            );
        }
        $this->assign('ext_cats', $ext_cats);

        # 获取品牌数据
        $brand_model = M('Brand');
        $brand_list = $brand_model->select();
        $this->assign('brand_list', $brand_list);

        # 获取会员级别
        $ml_model = M('MemberLevel');
        $ml_list = $ml_model->select();
        $this->assign('ml_list', $ml_list);

        # 获取会员价格数据
        $mp_model = M('MemberPrice');
        $mp_list = $mp_model->where(array('goods_id' => array('eq', $id)))->select();
        $mps = array();
        foreach ($mp_list as $v) {
            $mps[$v['level_id']] = $v['price'] === '-1.00' ? '-1' : $v['price'];
        }
        $this->assign('mps', $mps);

        # 获取商品类型
        $type_model = M('Type');
        $type_list = $type_model->select();
        $this->assign('type_list', $type_list);

        # 获取商品相册
        $goods_pics_model = M('GoodsPics');
        $goods_pics = $goods_pics_model->where(array('goods_id' => array('eq', $id)))->select();
        $this->assign('goods_pics', $goods_pics);

        $this->setPageInfo('修改商品', '商品列表', U('lst?p=' . I('get.p')));
        $this->display();
    }

    public function delete() {
        $model = D('Admin/Goods');
        if ($model->delete(I('get.id', 0)) !== FALSE) {
            $this->success('删除成功！', U('recyclelst', array('p' => I('get.p', 1))));
            exit;
        } else {
            $this->error($model->getError());
        }
    }

    public function lst() {
        $model = D('Admin/Goods');
        $data = $model->search();
        $this->assign(array(
            'data' => $data['data'],
            'page' => $data['page'],
        ));

        $this->setPageInfo('商品列表', '添加商品', U('add'));
        $this->display();
    }

    // 将商品放入回收站
    public function recycle() {
        $model = M('Goods');
        $sql = "update " . $model->getTableName() . " set is_delete = '1' where id='" . I('get.id') . "';";
        if ($model->execute($sql) !== FALSE) {
            $this->success('操作成功！', U('lst', array('p' => I('get.p', 1))));
            exit;
        } else {
            $this->error($model->getError());
        }
    }

    // 还原
    public function restore() {
        $model = M('Goods');
        $sql = "update " . $model->getTableName() . " set is_delete = '0' where id='" . I('get.id') . "'";
        if ($model->execute($sql) !== FALSE) {
            $this->success('操作成功！', U('recyclelst', array('p' => I('get.p', 1))));
            exit;
        } else {
            $this->error($model->getError());
        }
    }

    // 商品回收站
    public function recyclelst() {
        $model = D('Admin/Goods');
        $data = $model->search(20, 1);
        $this->assign(array(
            'data' => $data['data'],
            'page' => $data['page'],
        ));

        $this->setPageInfo('商品回收站', '商品列表', U('lst'));
        $this->display();
    }

    // 设置库存量
    public function storage() {
        $goods_id = I('get.id');
        $goods_number_model = M('GoodsNumber');
        //如果表单不为空，则提交表单数据
        if (IS_POST) {
            // 先删除旧数据
            $goods_number_model->where(array('goods_id'=>array('eq',$goods_id)))->delete();
            //再添加新数据
            $goods_attr_id = I('post.goods_attr_id',array(0));
            $goods_number = I('post.goods_number');
            $rate = count($goods_attr_id) / count($goods_number);
            $_i = 0;
            foreach ($goods_number as $v) {
                // 拼装数据
                $_arr = array();
                for($i=0;$i<$rate;++$i){
                    $_arr[] = $goods_attr_id[$_i];
                    $_i ++;
                }
                sort($_arr);
                $_str = implode(',', $_arr);
                // 入库
                $goods_number_model->add(array(
                    'goods_id' => $goods_id,
                    'goods_number' => $v,
                    'goods_attr_id' =>  $_str,
                ));
            }
            $this->success('操作成功！', U('lst', array('p' => I('get.p', 1))));
            exit;
        }

        //如果不存在表单数据，则显示表单
        //SELECT a.*,b.attr_name FROM `php34_goods_attr` a LEFT JOIN php34_attribute b on a.attr_id = b.id WHERE a.attr_id IN(SELECT attr_id FROM `php34_goods_attr` WHERE goods_id = 8 GROUP BY attr_id HAVING COUNT(attr_id) > 1) and a.goods_id = 8
        $goods_attr_model = M('GoodsAttr');
        $attr = $goods_attr_model->where(array('goods_id' => array('eq', $goods_id)))->group('attr_id')->having('count(attr_id)>1')->select();
        $attr_ids = array();
        foreach ($attr as $v) {
            $attr_ids[] = $v['attr_id'];
        }
        if (empty($attr_ids)) {
            $attr_ids = '';
        }
        $map['attr_id'] = array('IN', $attr_ids);
        $map['goods_id'] = array('eq', $goods_id);
        $attr = $goods_attr_model->field('a.*,b.attr_name')->alias('a')->where($map)->join('LEFT JOIN php34_attribute b on a.attr_id = b.id')->select();
        $data = array();
        foreach ($attr as $k => $v) {
            $data[$v['attr_id']] = array(
                'attr_name' => $v['attr_name'],
            );
        }
        foreach ($attr as $k => $v) {
            $data[$v['attr_id']]['attr_value'][$v['id']] = $v['attr_value'];
        }
        $this->assign('data', $data);
        
        // 如已有库存量数据则获取
        $goods_number_list = $goods_number_model->where(array('goods_id'=>array('eq',$goods_id)))->select();
        if($goods_number_list == null){
            $goods_number_list[0] = array(0);
        }
        $this->assign('goods_number_list', $goods_number_list);

        $this->setPageInfo('商品库存', '商品列表', U('lst'));
        $this->display();
    }

    public function getAttr() {
        $type_id = I('get.type_id');

        $attribute_model = M('Attribute');
        $attribute = $attribute_model->where(array('type_id' => array('eq', $type_id)))->select();

        $goods_id = I('get.goods_id');
        $goods_attr_model = M('GoodsAttr');
        $goods_attr = $goods_attr_model->where(array('goods_id' => array('eq', $goods_id)))->select();


        $data = array();

        foreach ($attribute as $k => $v) {
            $data[$k] = $v;

            if (isset($goods_attr)) {
                foreach ($goods_attr as $v1) {
                    if ($v['id'] == $v1['attr_id']) {
                        $data[$k]['attr_value_price'][] = array(
                            'goods_attr_id' => $v1['id'],
                            'attr_value' => $v1['attr_value'],
                            'attr_price' => $v1['attr_price'],
                        );
                    }
                }
            }

            if (!isset($data[$k]['attr_value_price'])) {
                $data[$k]['attr_value_price'][] = array(
                    'goods_attr_id' => '',
                    'attr_value' => '',
                    'attr_price' => '',
                );
            }
        }

        $this->ajaxReturn($data);
    }

    public function delImg() {
        $picid = I('get.picid');
        $goods_pics_model = M('GoodsPics');

        $images = $goods_pics_model->field('pic,sm_pic')->where(array('id' => array('eq', $picid)))->select();
        deleteImage($images);

        $result = $goods_pics_model->where(array('id' => array('eq', $picid)))->delete();
        $this->ajaxReturn($result);
    }

    public function delAttr() {
        $attrid = I('get.attrid');
        $goods_attr_model = M('GoodsAttr');

        $result = $goods_attr_model->delete($attrid);
        $this->ajaxReturn($result);
    }

}
