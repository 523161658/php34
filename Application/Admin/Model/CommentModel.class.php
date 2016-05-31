<?php

namespace Admin\Model;

use Think\Model;

class CommentModel extends Model {

    protected $insertFields = array('content', 'star', 'addtime', 'member_id', 'goods_id', 'used');
    protected $updateFields = array('id', 'content', 'star', 'addtime', 'member_id', 'goods_id', 'used');
    protected $_validate = array(
        array('content', 'require', '评论内容不能为空！', 1, 'regex', 3),
        array('content', '1,1000', '评论内容过长！', 1, 'length', 3),
        array('star', '/^[1-5]$/', '评分只能是1-5分！', 1, 'regex', 3),
    );
    
    protected  function _before_insert(&$data, $options) {
        $data['addtime'] = time();
        $data['member_id'] = session('member.id');
    }
}
