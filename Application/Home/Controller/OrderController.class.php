<?php

namespace Home\Controller;

class OrderController extends PlateformController {

    /**
     * 订单列表
     */
    public function lst() {
        $chooseGoods = I('post.chooseGoods', array());
        if ($chooseGoods) {
            cookie('chooseGoods', $chooseGoods, array('expire' => 86400, 'path' => '/', 'domain' => 'php34.com'));
        } else {
            $this->error('请选择要购买的商品！', U('Cart/lst'));
        }
        if (!session('member')) {
            redirect(U('goods/login'));
            exit;
        }



        $cartModel = D('Admin/Cart');
        $_cart = $cartModel->getCart();

        $data = array();
        foreach ($_cart as $k => $v) {
            $attr_str = $v['goods_id'] . '-' . $v['goods_attr_ids'];
            if (in_array($attr_str, $chooseGoods)) {
                $data[] = $_cart[$k];
            }
        }

        $this->assign('data', $data);
        $this->setPageInfo('订单', '订单', '订单', 0, array('fillin'), array('cart2'));
        $this->display();
    }

    /**
     * 新增订单
     */
    public function add() {
        if (IS_POST) {
            $orderModel = D('Admin/Order');
            if ($orderModel->create(I('post.'))) {
                if ($orderModel->add()) {
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
    public function success() {
        $html = $this->getAlipaySubmit();
        $this->assign('html', $html);
        $this->setPageInfo('订单', '订单', '订单', 1, array('success'));
        $this->display();
    }

    /**
     * 生成支付宝按钮
     */
    public function getAlipaySubmit() {
        /*         *
         * 功能：即时到账交易接口接入页
         * 版本：3.4
         * 修改日期：2016-03*08
         * 说明：
         * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
         * 该代码仅供学习和研究支付宝接口使用，只是提供一个参考。

         * ************************注意*****************

         * 如果您在接口集成过程中遇到问题，可以按照下面的途径来解决
         * 1、开发文档中心（https://doc.open.alipay.com/doc2/detail.htm?spm=a219a.7629140.0.0.KvddfJ&treeId=62&articleId=103740&docType=1）
         * 2、商户帮助中心（https://cshall.alipay.com/enterprise/help_detail.htm?help_id=473888）
         * 3、支持中心（https://support.open.alipay.com/alipay/support/index.htm）

         * 如果想使用扩展功能,请按文档要求,自行添加到parameter数组即可。
         * *********************************************
         */

        require_once("/PUBLIC/Alipay/alipay.config.php");
        require_once("/PUBLIC/Alipay/lib/alipay_submit.class.php");

        /*         * ************************请求参数************************* */
        //商户订单号，商户网站订单系统中唯一订单号，必填
        $out_trade_no = $_POST['WIDout_trade_no'];

        //订单名称，必填
        $subject = $_POST['WIDsubject'];

        //付款金额，必填
        $total_fee = $_POST['WIDtotal_fee'];

        //商品描述，可空
        $body = $_POST['WIDbody'];


        /*         * ********************************************************* */

        //构造要请求的参数数组，无需改动
        $parameter = array(
            "service" => $alipay_config['service'],
            "partner" => $alipay_config['partner'],
            "seller_id" => $alipay_config['seller_id'],
            "payment_type" => $alipay_config['payment_type'],
            "notify_url" => $alipay_config['notify_url'],
            "return_url" => $alipay_config['return_url'],
            "anti_phishing_key" => $alipay_config['anti_phishing_key'],
            "exter_invoke_ip" => $alipay_config['exter_invoke_ip'],
            "out_trade_no" => $out_trade_no,
            "subject" => $subject,
            "total_fee" => $total_fee,
            "body" => $body,
            "_input_charset" => trim(strtolower($alipay_config['input_charset']))
                //其他业务参数根据在线开发文档，添加参数.文档地址:https://doc.open.alipay.com/doc2/detail.htm?spm=a219a.7629140.0.0.kiX33I&treeId=62&articleId=103740&docType=1
                //如"参数名"=>"参数值"
        );

        //建立请求
        $alipaySubmit = new \AlipaySubmit($alipay_config);
        $html_text = $alipaySubmit->buildRequestForm($parameter, "get", "支付宝支付");
        return $html_text;
    }

    /**
     * 支付成功后，支付宝系统触发的功能，用于处理订单已支付的业务逻辑
     */
    public function response() {
        /*         *
         * 功能：支付宝服务器异步通知页面
         * 版本：3.3
         * 日期：2012-07-23
         * 说明：
         * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
         * 该代码仅供学习和研究支付宝接口使用，只是提供一个参考。


         * ************************页面功能说明*************************
         * 创建该页面文件时，请留心该页面文件中无任何HTML代码及空格。
         * 该页面不能在本机电脑测试，请到服务器上做测试。请确保外部可以访问该页面。
         * 该页面调试工具请使用写文本函数logResult，该函数已被默认关闭，见alipay_notify_class.php中的函数verifyNotify
         * 如果没有收到该页面返回的 success 信息，支付宝会在24小时内按一定的时间策略重发通知
         */

        require_once("/PUBLIC/alipay.config.php");
        require_once("/PUBLIC/lib/alipay_notify.class.php");

        //计算得出通知验证结果
        $alipayNotify = new \AlipayNotify($alipay_config);
        $verify_result = $alipayNotify->verifyNotify();

        if ($verify_result) {//验证成功
            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            //请在这里加上商户的业务逻辑程序代
            //——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
            //获取支付宝的通知返回参数，可参考技术文档中服务器异步通知参数列表
            //商户订单号
            $out_trade_no = $_POST['out_trade_no'];

            //支付宝交易号

            $trade_no = $_POST['trade_no'];

            //交易状态
            $trade_status = $_POST['trade_status'];


            if ($_POST['trade_status'] == 'TRADE_FINISHED') {
                //判断该笔订单是否在商户网站中已经做过处理
                //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
                //请务必判断请求时的total_fee、seller_id与通知时获取的total_fee、seller_id为一致的
                //如果有做过处理，不执行商户的业务程序
                //注意：
                //退款日期超过可退款期限后（如三个月可退款），支付宝系统发送该交易状态通知
                //调试用，写文本函数记录程序运行情况是否正常
                //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
            } else if ($_POST['trade_status'] == 'TRADE_SUCCESS') {
                //判断该笔订单是否在商户网站中已经做过处理
                //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
                //请务必判断请求时的total_fee、seller_id与通知时获取的total_fee、seller_id为一致的
                //如果有做过处理，不执行商户的业务程序
                //注意：
                //付款完成后，支付宝系统发送该交易状态通知
                //调试用，写文本函数记录程序运行情况是否正常
                //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
            }

            //——请根据您的业务逻辑来编写程序（以上代码仅作参考）——

            echo "success";  //请不要修改或删除
            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        } else {
            //验证失败
            echo "fail";

            //调试用，写文本函数记录程序运行情况是否正常
            //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
        }
    }

    /**
     * 支付成功后，页面跳转的地址
     */
    public function paySuccess() {
        
    }

}
