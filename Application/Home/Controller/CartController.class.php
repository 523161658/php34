<?php

namespace Home\Controller;

class CartController extends PlateformController {

    /**
     * 添加购物车
     */
    public function addcart() {
        $cartModel = D('Admin/Cart');
        if (IS_POST) {
            $goods_attr_id = I('post.goods_attr_id');
            if ($goods_attr_id) {
                sort($goods_attr_id);
                $goods_attr_ids = implode(',', $goods_attr_id);
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
        
//                echo "<pre>";
//        var_dump($data[0]['attr_str']);die;
        $this->assign('data',$data);
        # 设置页面属性
        $this->setPageInfo('购物车', '购物车', '购物车', 0, array('cart'), array('cart1'));
        $this->display();
    }
}
