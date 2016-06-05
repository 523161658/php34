<?php

namespace Home\Controller;

class OrderController extends PlateformController {

    /**
     * 订单列表
     */
    public function lst() {
        $chooseGoods = I('post.chooseGoods',array());
        if($chooseGoods){
            cookie('chooseGoods',$chooseGoods,array('expire'=>86400,'path'=>'/','domain'=>'php34.com'));        
        }else{
            $this->error('请选择要购买的商品！',U('Cart/lst'));
        }
        if(!session('member')){
            redirect(U('goods/login'));
            exit;
        }
        

        
        $cartModel = D('Admin/Cart');
        $_cart = $cartModel->getCart();
        
        $data = array();
        foreach($_cart as $k=>$v){
            $attr_str = $v['goods_id'].'-'.$v['goods_attr_ids'];
            if(in_array($attr_str,$chooseGoods)){
                $data[] = $_cart[$k];
            }
        }

        $this->assign('data',$data);
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
