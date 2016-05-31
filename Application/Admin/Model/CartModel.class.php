<?php
namespace Admin\Model;
use Think\Model;
class CartModel extends Model {
    /**
     * 添加商品到购物车
     * @param type $goods_id
     * @param type $goods_attr_ids
     * @param type $goods_number
     * @param type $member_id
     */
    public function addToCart($goods_id, $goods_attr_ids, $goods_number) {
        # 判断是否登陆，如果登陆，将商品添加到数据库
        if (session('member')) {
            # 查看购物车数据库是否有该商品
            $has = $this->where(array('goods_id'=>array('eq',$goods_id),'goods_attr_ids'=>array('eq',$goods_attr_ids),'member_id'=>session('member.id')))->find();
            if($has){
                $this->where(array('id'=>array('eq',$has['id'])))->setInc('goods_number',$goods_number);
            }else{
                $data= array(
                    'goods_id' => $goods_id,
                    'goods_attr_ids' => $goods_attr_ids,
                    'goods_number' => $goods_number,
                    'member_id' => session('member.id'),
                );
                $this->add($data);
            }
        } else {
            # 如果没有登陆，将商品添加到cookie中
            $_cart = cookie('cart') != '' ? unserialize(cookie('cart')) : array();
            $_cart[$goods_id . '-' . $goods_attr_ids] += $goods_number;
            cookie('cart', serialize($_cart), array('expire' => 86400, 'path' => '/', 'domain' => 'php34.com'));
        }
    }
    
    /**
     * 获取购物车数据
     */
    public function getCart(){
        # 判断是否登录，如果登录，从数据库中获取，如果未登录，从COOKIE中获取
        if(session('member')){
            $cart = $this->where(array('member_id'=>array('eq',session('member.id'))))->select();
        }else{
            $_cart = cookie('cart') != '' ? unserialize(cookie('cart')) : array();
            $cart = array();
            foreach($_cart as $k=>$v){
                $str = explode('-',$k);
                $cart[] = array(
                    'goods_id' => $str[0],
                    'goods_attr_ids' => $str[1],
                    'goods_number' => $v,
                    'member_id' => 0
                );
            }
        }

        # 根据$cart中的数据获取购物车页面所需要的所有数据
        $goodsModel = D('Admin/Goods');
        foreach($cart as $k=>$v){
            $sql = 'SELECT concat(b.attr_name,":",a.attr_value) attr_str FROM `php34_goods_attr` a LEFT JOIN php34_attribute b on a.attr_id = b.id WHERE a.id in(' . $v['goods_attr_ids'] . ');';
            $data = $this->query($sql);
            $cart[$k]['attr_str'] = $data;
            
            $info = $goodsModel->field('sm_logo,goods_name,shop_price')->find($v['goods_id']);
            $cart[$k]['sm_logo'] = $info['sm_logo'];
            $cart[$k]['goods_name'] = $info['goods_name'];
            if($memberPrice = $goodsModel->getMemberPrice($v['goods_id'])){
                $price = $memberPrice;
            }else{
                $price = $info['shop_price'];
            }
            $cart[$k]['goods_price'] = $price;
        }
        
        if($cart){
            return $cart;
        }else{
            return '';
        }
    }

}
