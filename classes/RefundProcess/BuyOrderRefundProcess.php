<?php
/**
 * Created by PhpStorm.
 * User: royalwang
 * Date: 2018/12/27
 * Time: 10:57
 */

namespace Ecjia\App\Refund\RefundProcess;

use RC_Notification;
use RC_DB;

/**
 * 消费订单退款成功后续处理
 * Class BuyOrderRefundProcess
 * @package Ecjia\App\Refund\RefundProcess
 */
class BuyOrderRefundProcess
{

    public function __construct()
    {

    }

    public function run()
    {
        //更新打款表 update_refund_payrecord
        $this->updateRefundPayrecord();

        //更新售后订单表 update_refund_order
        $this->updateRefundOrder();
        //售后订单状态变动日志表 update_refund_status_log
        $this->updateRefundStatusLog();

        //更新订单操作表 update_order_action
        $this->updateOrderAction();
        //普通订单状态变动日志表 update_order_status_log
        $this->updateOrderStatusLog();

        //记录到结算表 update_merchant_commission
        $this->updateMerchantCommission();
        //更新商家会员 update_merchant_customer
        $this->updateMerchantCustomer();

        //短信告知用户退款退货成功 send_sms_notice
        $this->sendSmsNotice();
        //消息通知 send_datatbase_notice
        $this->sendDatatbaseNotice();
    }

    /**
     * 更新打款表
     */
    protected function updateRefundPayrecord()
    {
        $data = array(
            'action_back_type'			=>	$back_type,
            'action_back_time'			=>	RC_Time::gmtime(),
            'action_back_content'		=>	$back_content,
            'action_user_id'	=>	$_SESSION['admin_id'],
            'action_user_name'	=>	$_SESSION['admin_name']
        );
        RC_DB::table('refund_payrecord')->where('id', $id)->update($data);
    }

    /**
     * 更新售后订单表
     */
    protected function updateRefundOrder()
    {
        $data = array(
            'refund_status'	=> 2,
            'refund_time'	=> RC_Time::gmtime(),
        );
        RC_DB::table('refund_order')->where('refund_id', $refund_id)->update($data);
    }

    /**
     * 售后订单状态变动日志表
     */
    protected function updateRefundStatusLog()
    {
        RefundStatusLog::refund_payrecord(array('refund_id' => $refund_id, 'back_money' => $back_money_total));
    }

    /**
     * 更新订单操作表
     */
    protected function updateOrderAction()
    {
        //更新订单操作表
        $data = array(
            'refund_id' 		=> $refund_id,
            'action_user_type'	=>	'admin',
            'action_user_id'	=>  $_SESSION['admin_id'],
            'action_user_name'	=>	$_SESSION['admin_name'],
            'status'		    =>  1,
            'refund_status'		=>  2,
            'return_status'		=>  $return_status,
            'action_note'		=>  $action_note,
            'log_time'			=>  RC_Time::gmtime(),
        );
        RC_DB::table('refund_order_action')->insertGetId($data);
    }

    /**
     * 普通订单状态变动日志表
     */
    protected function updateOrderStatusLog()
    {
        $order_id = RC_DB::table('refund_order')->where('refund_id', $refund_id)->pluck('order_id');
        OrderStatusLog::refund_payrecord(array('order_id' => $order_id, 'back_money' => $back_money_total));
    }

    /**
     * 记录到结算表
     */
    protected function updateMerchantCommission()
    {
        RC_Api::api('commission', 'add_bill_queue', array('order_type' => 'refund', 'order_id' => $refund_order['refund_id']));
    }

    /**
     * 更新商家会员
     */
    protected function updateMerchantCustomer()
    {
        if (!empty($user_id) && !empty($refund_order['store_id'])) {
            RC_Api::api('customer', 'store_user_buy', array('store_id' => $refund_order['store_id'], 'user_id' => $user_id));
        }
    }

    /**
     * 短信告知用户退款退货成功
     */
    protected function sendSmsNotice()
    {
        $user_info = RC_DB::table('users')->where('user_id', $user_id)->select('user_name', 'pay_points', 'user_money', 'mobile_phone')->first();
        if (!empty($user_info['mobile_phone'])) {
            $options = array(
                'mobile' => $user_info['mobile_phone'],
                'event'	 => 'sms_refund_balance_arrived',
                'value'  =>array(
                    'user_name' 	=> $user_info['user_name'],
                    'amount' 		=> $back_money_total,
                    'user_money' 	=> $user_info['user_money'],
                ),
            );
            RC_Api::api('sms', 'send_event_sms', $options);
        }
    }

    /**
     * 消息通知
     */
    protected function sendDatatbaseNotice()
    {
        $orm_user_db = RC_Model::model('orders/orm_users_model');
        $user_ob = $orm_user_db->find($user_id);

        if ($user_ob) {
            $user_refund_data = array(
                'title'	=> '退款到余额',
                'body'	=> '尊敬的'.$user_info['user_name'].'，退款业务已受理成功，退回余额'.$back_money_total.'元，目前可用余额'.$user_info['user_money'].'元。',
                'data'	=> array(
                    'user_id'				=> $user_id,
                    'user_name'				=> $user_info['user_name'],
                    'amount'				=> $back_money_total,
                    'formatted_amount' 		=> price_format($back_money_total),
                    'user_money'			=> $user_info['user_money'],
                    'formatted_user_money'	=> price_format($user_info['user_money']),
                    'refund_id'				=> $refund_order['refund_id'],
                    'refund_sn'				=> $refund_order['refund_sn'],
                ),
            );

            $push_refund_data = new RefundBalanceArrived($user_refund_data);
            RC_Notification::send($user_ob, $push_refund_data);
        }
    }


}