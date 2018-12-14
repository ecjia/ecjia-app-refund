<?php

namespace Ecjia\App\Refund;

use RC_DB;
use RC_Time;
use ecjia;
use RC_Lang;
use RC_Api;
use RC_Loader;
use RC_Logger;
use OrderStatusLog;
use order_ship;
use RefundStatusLog;

/**
 * 订单退款完成；更新各项数据
 */
class HandleRefundedUpdateData
{    
	
	/**
	 * 退款完成，更新各项数据；最终返回打印数据
	 * @param array $refund_result['order_info'] 订单信息       必传
	 * @param array $refund_result['refund_payrecord_info'] 订单退款，打款信息   必传
	 * @param array $refund_result['refund_order_info'] 退款申请单信息    必传
	 * @param array $refund_result['notify_data'] 通知数据
	 * @param string $refund_result['back_type'] 退款类型
	 * @param string $refund_result['refund_way'] 退款方式   必传
	 * @param int $refund_result['is_cashdesk'] 是否是收银台申请退款
	 * @return array
	 */
	public static function updateRefundedData($refund_result) 
	{
		/**
		 * 退款成功后，后续操作
		 * 1、退积分 (RC_Api)
		 * 2、更新打款表 UpdateRefundPayrecord()
		 * 3、更新订单日志状态 & 操作记录表 UpdateOrderStatus()
		 * 4、更新售后订单状态日志 & 操作记录表 UpdateRefundOrderStatus()
		 * 5、更新结算记录 UpdateBillOrder()
		 * 6、更新商家会员 UpdateMerchantUser()
		 * 7、退款短信通知 SendSmsNotice()
		 * 8、返回打印数据 PrintData()
		 */
		
		$order_info 			= $refund_result['order_info'];
		$refund_payrecord_info 	= $refund_result['refund_payrecord_info'];
		$refund_order_info		= $refund_result['refund_order_info'];
		
		//退款退积分
		$bak_integral = RC_Api::api('finance', 'refund_back_pay_points', array('refund_id' => $refund_order_info['refund_id']));
		if (is_ecjia_error($bak_integral)) {
			return $bak_integral;
		}
		
		//更新打款表
		self::UpdateRefundPayrecord($refund_result);
		
		//更新订单日志状态 & 操作记录表
		self::UpdateRefundOrderStatus($refund_result);
		
		//更新结算记录
		self::UpdateBillOrder($refund_order_info['refund_id']);
		
		//更新商家会员
		self::UpdateMerchantUser($refund_order_info);
		
		//退款短信通知
		self::SendSmsNotice();
		
		$refund_print_data = self::RefundPrintData();
	}
	
	/**
	 * 更新打款表
	 * @param array $refund_result['order_info'] 订单信息       
	 * @param array $refund_result['refund_payrecord_info'] 订单退款，打款信息   必传
	 * @param array $refund_result['refund_order_info'] 退款申请单信息    
	 * @param array $refund_result['notify_data'] 通知数据
	 * @param string $refund_result['back_type'] 退款类型
	 * @param string $refund_result['refund_way'] 退款方式   必传
	 * @param int $refund_result['is_cashdesk'] 是否是收银台申请退款
	 */
	public static function UpdateRefundPayrecord($refund_result)
	{
		if ($refund_result['is_cashdesk']) {
			if ($refund_result['refund_way'] == 'cash') {
				$action_back_content = '收银台申请退款，现金退款成功';
			} elseif ($refund_result['refund_way'] == 'original')  {
				$action_back_content = '收银台申请退款，原路退回成功';
			} else {
				$action_back_content = '';
			}
		} else {
			if ($refund_result['refund_way'] == 'cash') {
				$action_back_content = '申请退款，现金退款成功';
			} elseif ($refund_result['refund_way'] == 'original')  {
				$action_back_content = '申请退款，原路退回成功';
			} else {
				$action_back_content = '';
			}
		}
		
		//更新打款表
		$data = array(
				'action_back_type' 		=> $refund_result['back_type'],
				'action_back_time' 		=> RC_Time::gmtime(),
				'action_back_content' 	=> $action_back_content,
				'action_user_type' 		=> 'merchant',
				'action_user_id' 		=> empty($refund_result['staff_id']) ? 0 : $refund_result['staff_id'],
				'action_user_name' 		=> empty($refund_result['staff_name']) ? 0 : $refund_result['staff_name'],
		);
		
		RC_DB::table('refund_payrecord')->where('id', $refund_result['refund_payrecord_info']['id'])->update($data);
	}
	
	
	/**
	 * 更新售后订单状态日志 & 操作记录表
	 */
	public static function UpdateRefundOrderStatus($refund_result)
	{
		//更新售后订单表
		$data = array(
				'refund_status' => Ecjia\App\Refund\RefundStatus::TRANSFERED,
				'refund_time' => RC_Time::gmtime(),
		);
		
		RC_DB::table('refund_order')->where('refund_id', $refund_result['refund_order_info']['refund_id'])->update($data);
		
		
		$back_money_total 	= $refund_result['refund_payrecord_info']['back_money_total'];
		$back_integral 		= $refund_result['refund_order_info']['integral'];
		
		//更新售后订单操作表
		$action_note = '退款金额已退回' . $back_money_total . '元，退回积分为：' . $back_integral;
		$data = array(
				'refund_id' 			=> $refund_result['refund_order_info']['refund_id'],
				'action_user_type' 		=> 'merchant',
				'action_user_id' 		=> empty($refund_result['staff_id']) ? 0 : $refund_result['staff_id'],
				'action_user_name' 		=> empty($refund_result['staff_name']) ? '' : $refund_result['staff_name'],
				'status' 				=> Ecjia\App\Refund\RefundStatus::AGREE,
				'refund_status' 		=> Ecjia\App\Refund\RefundStatus::TRANSFERED,
				'return_status' 		=> Ecjia\App\Refund\RefundStatus::CONFIRM_RECV,
				'action_note' 			=> $action_note,
				'log_time' 				=> RC_Time::gmtime(),
		);
		RC_DB::table('refund_order_action')->insertGetId($data);
		
		//售后订单状态变动日志表
		RC_Loader::load_app_class('RefundStatusLog', 'refund', false);
		RefundStatusLog::refund_payrecord(array('refund_id' => $refund_result['refund_order_info']['refund_id'], 'back_money' => $back_money_total));
	} 
	
	
	/**
	 * 更新结算记录
	 */
	public static function UpdateBillOrder($refund_id = 0)
	{
		//更新结算记录
		$res = RC_Api::api('commission', 'add_bill_queue', array('order_type' => 'refund', 'order_id' => $refund_id));
		if (is_ecjia_error($res)) {
			return $res;
		}
	}
	
	/**
	 * 更新商家会员
	 */
	public static function UpdateMerchantUser($refund_order_info = array())
	{
		//更新商家会员
		if ($refund_order_info['user_id'] > 0 && $refund_order_info['store_id'] > 0) {
			$res = RC_Api::api('customer', 'store_user_buy', array('store_id' => $refund_order_info['store_id'], 'user_id' => $refund_order_info['user_id']));
			if (is_ecjia_error($res)) {
				return $res;
			}
		}
	}
	
	/**
	 * 退款短信通知
	 */
	public static function SendSmsNotice()
	{
		
	}
	
	/**
	 * 退款打印数据
	 */
	public static function RefundPrintData()
	{
		
	}
	
}