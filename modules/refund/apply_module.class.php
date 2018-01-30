<?php
defined('IN_ECJIA') or exit('No permission resources.');

/**
 * 订单退款申请
 * @author zrl
 *
 */
class apply_module extends api_front implements api_interface {
    public function handleRequest(\Royalcms\Component\HttpKernel\Request $request) {
		
		$this->authSession();
		if ($_SESSION['user_id'] <= 0 ) {
			return new ecjia_error(100, 'Invalid session');
		}
		$order_id			= $this->requestData('order_id', 0);
		$refund_type		= $this->requestData('refund_type');
		$reason_id			= $this->requestData('reason_id', '');
		$refund_description = $this->requestData('refund_description');
		
		if (empty($order_id) || empty($refund_type) || empty($reason_id)) {
			return new ecjia_error('invalid_parameter', '参数错误');
		}
		
		$order_info = RC_Api::api('orders', 'order_info', array('order_id' => $order_id));
		
		if (is_ecjia_error($order_info)) {
			return $order_info;
		}
		
		if (empty($order_info)) {
			return new ecjia_error('not_exists_info', '订单信息不存在！');
		}
		
		//当前订单是否可申请售后
		if (in_array($order_info['pay_status'], array(PS_UNPAYED))
			|| in_array($order_info['order_status'], array(OS_CANCELED, OS_INVALID, OS_RETURNED))
			|| ($order_info['is_delete'] == '1')
		) {
			return new ecjia_error('error_apply', '当前订单不可申请售后！');
		}
		
		//当前订单有没申请过售后；有的话，都是处于什么状态
		RC_Loader::load_app_class('order_refund', 'refund', false);
		$order_refund_info = order_refund::currorder_refund_info($order_id);
		
		if (!empty($order_refund_info)) {
			if (($order_refund_info['status'] == Ecjia\App\Refund\RefundStatus::UNCHECK) || (($order_refund_info['status'] == Ecjia\App\Refund\RefundStatus::AGREE) && ($order_refund_info['refund_staus'] == Ecjia\App\Refund\RefundStatus::UNTRANSFER))) {
				return new ecjia_error('error_apply', '当前订单已申请了售后！');
			}
		}
		
		//退款编号
		$refund_sn = order_refund::get_refund_sn();
		//配送方式信息
		if (!empty($order_info['shipping_id'])) {
			$shipping_id = intval($order_info['shipping_id']);
			$shipping_info = ecjia_shipping::pluginData($shipping_id);
			$shipping_code = $shipping_info['shipping_code'];
		} else {
			$shipping_code = NULL;
		}
		
		//支付方式信息
		if (!empty($order_info['pay_id'])) {
			$payment_info = with(new Ecjia\App\Payment\PaymentPlugin)->getPluginDataById($order_info['pay_id']);
			$pay_code = $payment_info['pay_code'];
		} else {
			$pay_code = NULL;
		}
		
		//退货状态
		if ($refund_type == 'refund') {
			$return_status = 0;
			$return_time = 0;
		} elseif ($refund_type == 'return') {
			$return_status = 1;
			$return_time = RC_Time::gmtime();
		}
		
		$data = array(
			'store_id'		=> $order_info['store_id'],
			'user_id'		=> $order_info['user_id'],
			'user_name'		=> $order_info['user_name'],
			'refund_type'	=> $refund_type,
			'refund_sn'		=> $refund_sn,
			'order_type'	=> '',
			'order_id'		=> $order_id,
			'order_sn'		=> $order_info['order_sn'],
			'shipping_code'	=> $shipping_code,
			'shipping_name'	=> $order_info['shipping_name'],
			'shipping_fee'	=> $order_info['shipping_fee'],
			'insure_fee'	=> $order_info['insure_fee'],
			'pay_code'		=> $pay_code,
			'pay_name'		=> $payment_info['pay_name'],
			'goods_amount'	=> $order_info['goods_amount'],
			'pay_fee'		=> $order_info['pay_fee'],
			'pack_id'		=> $order_info['pack_id'],
			'pack_fee'		=> $order_info['pack_fee'],
			'card_id'		=> $order_info['card_id'],
			'card_fee'		=> $order_info['card_fee'],
			'bonus_id'		=> $order_info['bonus_id'],
			'bonus'			=> $order_info['bonus'],
			'surplus'		=> $order_info['surplus'],
			'integral'		=> $order_info['integral'],
			'integral_money'=> $order_info['integral_money'],
			'discount'		=> $order_info['discount'],
			'inv_tax'		=> $order_info['tax'],
			'order_amount'	=> $order_info['order_amount'],
			'money_paid'	=> $order_info['money_paid'],
			'status'		=> 0,
			'refund_status'	=> Ecjia\App\Refund\RefundStatus::UNTRANSFER,
			'refund_content'=> $refund_description,
			'refund_reason'	=> $reason_id,
			'refund_time'	=> RC_Time::gmtime(),
			'return_status'	=> $return_status,
			'return_time'	=> $return_time,
			'add_time'		=> RC_Time::gmtime(),
			'referer'		=> $order_info['referer']
		);
		
		$refund_id = RC_DB::table('refund_order')->insertGetId($data);
		
		//退商品
		if ($refund_type == 'return') {
			//获取订单商品
			$order_goods = order_refund::currorder_goods_list($order_id);
			if (!empty($order_goods)) {
				foreach ($order_goods as $row) {
					$back_goods_data = array(
							'rec_id'		=> $row['rec_id'],
							'back_id'		=> $refund_id,
							'goods_id'		=> $row['goods_id'],
							'product_id'	=> $row['product_id'],
							'goods_name'	=> $row['goods_name'],
							'goods_sn'		=> $row['goods_sn'],
							'is_real'		=> $row['is_real'],
							'send_number'	=> $row['send_number'],
							'goods_attr'	=> $row['$row']
					);
					$back_goods_id = RC_DB::table('back_goods')->insertGetId($back_goods_data);
				}
			}
		}
		
		

// 		$time = RC_Time::gmtime();
// 		$order_return = RC_Model::model('orders/order_return_model')->insert(array(
// 			'goods_id'	=> $order_goods_info['goods_id'],
// 			'user_id'	=> $_SESSION['user_id'],
// 			'rec_id'	=> $order_goods_info['rec_id'],
// 			'return_sn'	=> get_order_sn(),
// 			'order_id'	=> $order_id,
// 			'order_sn'	=> $order_info['order_sn'],
// 			'cause_id'	=> $reason_id,
// 			'back'		=> $return_type == 'return' ? 1 : 0,
// 			'exchange'	=> $return_type == 'exchange' ? 1 : 0,
// 			'return_type'	=> $return_t,
// 			'apply_time'	=> $time,
// 			'should_return' => $order_goods_info['goods_price']*$return_number,
// 			'remark'	=> $return_description,
// 			'country'	=> $country,
// 			'province'	=> $province,
// 			'city'		=> $city,
// 			'district'	=> $district,
// 			'address'	=> $address,
// 			'phone'		=> $phone,
// 			'addressee'	=> $consignee,
// 		));
// 		$return_goods = RC_Model::model('orders/return_goods_model')->insert(array(
// 			'rec_id'		=> $order_goods_info['rec_id'],
// 			'ret_id'		=> $order_return,
// 			'goods_id'		=> $order_goods_info['goods_id'],
// 			'product_id'	=> $order_goods_info['product_id'],
// 			'goods_name'	=> $order_goods_info['goods_name'],
// 			'goods_sn'		=> $order_goods_info['goods_sn'],
// 			'is_real'		=> $order_goods_info['is_real'],
// 			'goods_attr'	=> $order_goods_info['goods_attr'],
// 			'attr_id'		=> $order_goods_info['goods_attr_id'],
// 			'return_type'	=> $return_t,
// 			'return_number'	=> $return_number,
// 		));
// 		RC_Model::model('orders/order_return_extend_model')->insert(array(
// 			'ret_id'		=> $order_return,
// 			'return_number' => $return_number,
// 		));
		
		
// 		if (!empty($_FILES)) {
// 			$save_path = 'data/return_images';
// 			$upload = RC_Upload::uploader('image', array('save_path' => $save_path, 'auto_sub_dirs' => true));
		
// 			$image_info	= $upload->batch_upload($_FILES);
// 			foreach ($image_info as $key => $val) {
// 				if (!empty($val)) {
// 					$image_url	= $upload->get_position($image_info[$key]);
// 					RC_Model::model('orders/return_images_model')->insert(array(
// 																			'rg_id'		=> $return_goods,
// 																			'rec_id'	=> $rec_id,
// 																			'user_id'	=> $_SESSION['user_id'],
// 																			'img_file'	=> $image_url,
// 																			'add_time'	=> RC_Time::gmtime()
// 																));
// 				}
// 			}
// 		}
// 		return array('return_id' => $order_return, 'apply_time' => RC_Time::local_date(ecjia::config('time_format'), $time));
		
	}
}
// end