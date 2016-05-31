<?php
namespace Home\Controller;
class IndexController extends PlateformController {
    public function index(){
        $goods_model = D('Admin/Goods');
        $crazy_goods = $goods_model->getCrazyGoods();
        $hot_goods = $goods_model->getHotGoods();
        $best_goods = $goods_model->getBestGoods();
        $new_goods = $goods_model->getNewGoods();
        
        $this->assign(array(
            'crazy_goods' =>  $crazy_goods,
            'hot_goods' =>  $hot_goods,
            'best_goods' =>  $best_goods,
            'new_goods' =>  $new_goods,
        ));
        $this->setPageInfo('首页', '商城', '商城',1,array('index'),array('index'));
        $this->display();
    }
}