<?php

namespace Home\Controller;

class CartController extends PlateformController {

    /**
     * 添加购物车
     */
    public function addcart() {
        $cartModel = D('Admin/Cart');
        if (IS_POST) {
            $goods_attr_id = I('post.goods_attr_id',array(0));
            if ($goods_attr_id) {
                sort($goods_attr_id);
                $goods_attr_ids = implode(',', $goods_attr_id);
            }else{
                $goods_attr_ids = 0;
            }
            $cartModel->addToCart(I('post.goods_id'), $goods_attr_ids, I('post.goods_number'));

            redirect(U('lst'));
        }
    }
    
    /**
     * 购物车列表
     */
    public function lst() {
        $cartModel = D('Admin/Cart');
        $data = $cartModel->getCart();
        // var_dump($data);
        $this->assign('data',$data);
        # 设置页面属性
        $this->setPageInfo('购物车', '购物车', '购物车', 0, array('cart'), array('cart1'));
        $this->display();
    }

    /**
     * 更新购物车数据库
     */
    public function edit(){
        $goods_id = I('post.goods_id');
        $goods_attr_ids = I('post.goods_attr_ids');
        $goods_number = I('post.goods_number');

        $cartModel = D('Admin/Cart');
        $cartModel->updataCart($goods_id,$goods_attr_ids,$goods_number);
        
        echo $goods_number;
    }
    
    /**
     * 删除购物车
     */
    public function delete(){
        $goods_id = I('get.goods_id');
        $goods_attr_ids = I('get.goods_attr_ids');

        $cartModel = D('Admin/Cart');
        $cartModel->deleteCart($goods_id,$goods_attr_ids);
        
        redirect(U('/home/cart/lst'));
    }
}
