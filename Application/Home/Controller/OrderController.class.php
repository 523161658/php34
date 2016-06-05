<?php

namespace Home\Controller;

class OrderController extends PlateformController {

    /**
     * 订单列表
     */
    public function lst() {
        if(!session('member')){
            redirect(U('goods/login'));
            exit;
        }
        $cartModel = D('Admin/Cart');
        $_cart = $cartModel->getCart();
        if($_cart == ''){
            $this->error('购物车中无数据，请先添加商品！','/');
            exit;
        }

        $this->assign('data',$_cart);
        $this->setPageInfo('订单', '订单', '订单', 0, array('fillin'), array('cart2'));
        $this->display();
    }

    /**
     * 新增订单
     */
    public function add(){
        if(IS_POST){
            $orderModel = D('Admin/Order');
            if($orderModel->create(I('post.'))){
                if($orderModel->add()){
                    $this->redirect('success');
                    //exit;
                }
            }
            $this->error($orderModel->getError());
        }
    }

    /**
     * 生成订单 
     */
    public function success(){
        $this->setPageInfo('订单', '订单', '订单',1,array('success'));
        $this->display();
    }
}
