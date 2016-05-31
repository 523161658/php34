<?php
namespace Home\Controller;
class CommentController extends PlateformController {
    public function add(){
        $result = array();
        if(!session('member')){
            $result['code'] = '0';
            $result['message'] = '您还没有登陆，请先登陆！';
            $this->ajaxReturn($result);
        }
    	if(IS_POST){
            $comment_model = D('Admin/Comment');
            if($comment_model->create()){
                if($comment_id = $comment_model->add()){
                    $result['code'] = '1';
                    $result['message'] = '评论成功！';

                    $member_model = M('Member');
                    $face = $member_model->where(array('id'=>array('eq',session('member.id'))))->getField('face');
                    $result['face'] = $face !== '' ? '/Public/Uploads/Member/'.$face : C('DEFAULT_FACE');

                    $result['email'] = session('member.email');
                    $result['comment_time'] = date("Y-m-d H:i:s");
                    $result['comment_content'] = I('post.content');
                    $result['star'] = I('post.star');
                    $this->ajaxReturn($result);
                } 
            }
            $result['code'] = '0';
            $result['message'] = $comment_model->getError();
        }
        $this->ajaxReturn($result);
    }
    
    /**
     * 通过AJAX获取评论
     */
    public function getComment(){
        $curPage = I('get.p');
        $id = I('get.id');
        $perPage = 5;
        
        $comment_model = M('Comment');
        $comment = $comment_model->alias('a')->field('a.*,b.email,b.face,COUNT(c.comment_id) count_reply')->join('LEFT JOIN php34_member b on a.member_id = b.id  LEFT JOIN php34_reply c on c.comment_id = a.id')->group('a.id')->order('a.id desc')->where(array('a.goods_id'=>array('eq',$id)))->page($curPage,$perPage)->select();

        $this->ajaxReturn($comment);
    }
}