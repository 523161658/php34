<?php
namespace Home\Controller;
use Think\Controller;
class PlateformController extends Controller {
    public function setPageInfo($title,$keywords,$description,$is_show_nav=1,$css=array(),$js=array()){
        $this->assign('title',$title);
        $this->assign('keywords',$keywords);
        $this->assign('description',$description);
        $this->assign('is_show_nav',$is_show_nav);
        $this->assign('css',$css);
        $this->assign('js',$js);
    }
}