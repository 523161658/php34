<?php

namespace Home\Controller;

class SearchController extends PlateformController {

    public function search(){
        $categoryModel = M('Category');
        $cat = $categoryModel->find(I('get.id'));
        $data = array();
        if($cat['search_attr_id'] != ''){
            $attributeModel = M('Attribute');
            $data = $attributeModel->where(array('id'=>array('in',$cat['search_attr_id'])))->select();
            if(count($data)>0){
                $goodsAttrModel = M('GoodsAttr');
                foreach($data as $k=>$v){
                    $goods_attr = $goodsAttrModel->field('attr_value')->distinct(true)->where(array('attr_id'=>array('eq',$v['id'])))->select();
                    if(count($goods_attr)>0){
                        $data[$k]['attr_value'] = $goods_attr;
                    }else{
                        unset($data[$k]);
                    }
                }                  
            }
        }
        $this->assign('data',$data);
//        echo "<pre>";
//        var_dump($data);die;
        # 设置页面属性
        $this->setPageInfo('搜索页', '搜索', '搜索', 0, array('list', 'common'), array('list'));
        $this->display();
    }
}
