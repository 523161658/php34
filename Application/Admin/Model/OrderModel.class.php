<?php
namespace Admin\Model;
use Think\Model;
class OrderModel extends Model {
    protected $insertFields = array('shr_name','shr_province','shr_city','shr_area','shr_address','shr_tel','post_method','pay_method');
    protected $_validate = array(
        array('shr_name', 'require', '收货人姓名不能为空！', 1, 'regex', 3),
        array('shr_province', 'require', '省份不能为空！', 1, 'regex', 3),
        array('shr_city', 'require', '城市不能为空！', 1, 'regex', 3),
        array('shr_area', 'require', '地区不能为空！', 1, 'regex', 3),
        array('shr_address', 'require', '详细地址不能为空！', 1, 'regex', 3),
        array('shr_tel', 'require', '电话不能为空！', 1, 'regex', 3),
        array('post_method', 'require', '邮寄方式不能为空！', 1, 'regex', 3),
        array('pay_method', 'require', '支付方式不能为空！', 1, 'regex', 3),
    );
    
    // 将订单基本数据插入数据库中
    protected function _before_insert(&$data, $options) {
        $cartModel = D('Admin/Cart');
        $_cart = $cartModel->getCart();
        if($_cart == ''){
            $this->error = '购物车中无数据！';
            return false;
        }
        $this->fp = fopen('./order.lock','r');
        flock($this->fp,LOCK_EX);
        $goods_number_model = M('GoodsNumber');
        $total = 0;
        foreach($_cart as $k=>$v){
            $goods = $goods_number_model->field('goods_number')->where(array('goods_id'=>array('eq',$v['goods_id']),'goods_attr_id'=>array('eq',$v['goods_attr_ids'])))->find();
            if($goods['goods_number'] < $v['goods_number']){
                $this->error = $v['goods_name'].'商品库存不足！';
                return false;
            }
            $total_price += $v['goods_price'];
        }
        $data['member_id'] = session('member.id');
        $data['addtime'] = time();
        $data['total_price'] = $total_price;
        
        // 插入事物务管
        $this->startTrans();
    }
    
    // 将订单商品数据插入数据库中
    protected function _after_insert($data, $options) {
        $cartModel = D('Admin/Cart');
        $_cart = $cartModel->getCart();
        $order_goods_model = M('OrderGoods');
        $goods_number_model = M('GoodsNumber');
        foreach($_cart as $k=>$v){
            $order_goods = array();
            $order_goods['order_id'] = $data['id'];
            $order_goods['member_id'] = session('member.id');
            $order_goods['goods_id'] = $v['goods_id'];
            $order_goods['goods_attr_id'] = $v['goods_attr_ids'];
            $order_goods['goods_attr_str'] = serialize($v['attr_str']);
            $order_goods['goods_price'] = $v['goods_price'];
            $order_goods['goods_number'] = $v['goods_number'];
            // 新增订单所对应的商品数据
            if(FALSE === $order_goods_model->add($order_goods)){
                $this->rollback();
                return false;
            }
            // 减少库存量
            if(FALSE === $goods_number_model->where(array('goods_id'=>array('eq',$v['goods_id']),'goods_attr_id'=>array('eq',$v['goods_attr_ids'])))->setDec('goods_number',$v['goods_number'])){
                $this->rollback();
                return false;
            }
            // 删除购物车数据
            if(FALSE === $cartModel->deleteCart($v['goods_id'],$v['goods_attr_ids'])){
                $this->rollback();
                return false;
            }
        }
        $this->commit();
        flock($this>fp,LOCK_UN);
        fclose($this->fp);
    }

}
