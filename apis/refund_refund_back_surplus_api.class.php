<?php
defined('IN_ECJIA') or exit('No permission resources.');
/**
 * 收银台退款退回余额
 * @author zrl
 *
 */
class refund_refund_back_surplus_api extends Component_Event_Api {
    /**
     * @param  array $options['refund_id']	退款申请id
     * @return array
     */
	public function call(&$options) {
		if (!is_array($options) || empty($options['refund_id'])) {
			return new ecjia_error('invalid_parameter', '调用api文件,refund_back_surplus,参数无效');
		}
		return $this->back_surplus_operate($options);
	}
	
	
	/**
	 * 售后申请单生成
	 * @param   array $options	条件参数
	 * @return  bool   
	 */
	
	private function back_surplus_operate($options) {
		RC_Loader::load_app_class('order_refund', 'refund', false);
		$refund_id = $options['refund_id'];
		if (!empty($refund_id)) {
			$refund_info = RC_DB::table('refund_order')->where('refund_id', $refund_id)->first();
			if (empty($refund_info)) {
				return new ecjia_error('refund_order_error', '退款申请单信息不存在！');
			}
			//打款表信息
			$payrecord_info = RC_DB::table('refund_payrecord')->where('refund_id', $refund_id)->first();
			if (empty($payrecord_info)) {
				return new ecjia_error('refund_payrecord_error', '退款申请单打款信息不存在！');
			}
			$back_money_total 	= $payrecord_info['back_money_total'];
			$back_integral 		= $payrecord_info[''];
			
			$action_note = '退款金额已退回余额'.$back_money_total.'元，退回积分为：'.$back_integral;
			//更新帐户变动记录
			$account_log = array (
					'user_id'			=> $refund_info['user_id'],
					'user_money'		=> $back_money_total,
					'pay_points'		=> 0,
					'change_time'		=> RC_Time::gmtime(),
					'change_desc'		=> '由于订单'.$refund_info['order_sn'].'退款，退款金额退回余额',
					'change_type'		=> ACT_REFUND,
					'from_type'			=> 'refund_back_integral',
					'from_value'		=> $refund_info['order_sn'],
			);
			RC_DB::table('account_log')->insertGetId($account_log);
			//用户账户余额更新
			RC_DB::table('users')->where('user_id', $refund_info['user_id'])->increment('user_money', $back_money_total);
			
			return true;
		}
	}
}

// end