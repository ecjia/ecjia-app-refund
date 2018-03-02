<?php
//
//    ______         ______           __         __         ______
//   /\  ___\       /\  ___\         /\_\       /\_\       /\  __ \
//   \/\  __\       \/\ \____        \/\_\      \/\_\      \/\ \_\ \
//    \/\_____\      \/\_____\     /\_\/\_\      \/\_\      \/\_\ \_\
//     \/_____/       \/_____/     \/__\/_/       \/_/       \/_/ /_/
//
//   上海商创网络科技有限公司
//
//  ---------------------------------------------------------------------------------
//
//   一、协议的许可和权利
//
//    1. 您可以在完全遵守本协议的基础上，将本软件应用于商业用途；
//    2. 您可以在协议规定的约束和限制范围内修改本产品源代码或界面风格以适应您的要求；
//    3. 您拥有使用本产品中的全部内容资料、商品信息及其他信息的所有权，并独立承担与其内容相关的
//       法律义务；
//    4. 获得商业授权之后，您可以将本软件应用于商业用途，自授权时刻起，在技术支持期限内拥有通过
//       指定的方式获得指定范围内的技术支持服务；
//
//   二、协议的约束和限制
//
//    1. 未获商业授权之前，禁止将本软件用于商业用途（包括但不限于企业法人经营的产品、经营性产品
//       以及以盈利为目的或实现盈利产品）；
//    2. 未获商业授权之前，禁止在本产品的整体或在任何部分基础上发展任何派生版本、修改版本或第三
//       方版本用于重新开发；
//    3. 如果您未能遵守本协议的条款，您的授权将被终止，所被许可的权利将被收回并承担相应法律责任；
//
//   三、有限担保和免责声明
//
//    1. 本软件及所附带的文件是作为不提供任何明确的或隐含的赔偿或担保的形式提供的；
//    2. 用户出于自愿而使用本软件，您必须了解使用本软件的风险，在尚未获得商业授权之前，我们不承
//       诺提供任何形式的技术支持、使用担保，也不承担任何因使用本软件而产生问题的相关责任；
//    3. 上海商创网络科技有限公司不对使用本产品构建的商城中的内容信息承担责任，但在不侵犯用户隐
//       私信息的前提下，保留以任何方式获取用户信息及商品信息的权利；
//
//   有关本产品最终用户授权协议、商业授权与技术服务的详细内容，均由上海商创网络科技有限公司独家
//   提供。上海商创网络科技有限公司拥有在不事先通知的情况下，修改授权协议的权力，修改后的协议对
//   改变之日起的新授权用户生效。电子文本形式的授权协议如同双方书面签署的协议一样，具有完全的和
//   等同的法律效力。您一旦开始修改、安装或使用本产品，即被视为完全理解并接受本协议的各项条款，
//   在享有上述条款授予的权力的同时，受到相关的约束和限制。协议许可范围以外的行为，将直接违反本
//   授权协议并构成侵权，我们有权随时终止授权，责令停止损害，并保留追究相关责任的权力。
//
//  ---------------------------------------------------------------------------------
//
defined('IN_ECJIA') or exit('No permission resources.');

/**
 * 选择自选快递返还方式
 * @author 
 * zrl
 */
class express_module extends api_front implements api_interface {
    public function handleRequest(\Royalcms\Component\HttpKernel\Request $request) {	
    	$this->authSession();
    	$user_id = $_SESSION['user_id'];
    	if ($user_id < 1 ) {
    	    return new ecjia_error(100, 'Invalid session');
    	}
    	
		$refund_sn			= trim($this->requestData('refund_sn', ''));
		$recipient_address 	= trim($this->requestData('recipient_address', ''));
		$recipients			= trim($this->requestData('recipients', ''));
		$contact_phone		= trim($this->requestData('contact_phone', ''));
		$shipping_name		= trim($this->requestData('shipping_name', ''));
		$shipping_sn		= trim($this->requestData('shipping_sn', ''));
		
		
		if (empty($refund_sn)) {
			return new ecjia_error('invalid_parameter', RC_Lang::get('orders::order.invalid_parameter'));
		}
		RC_Loader::load_app_class('order_refund', 'refund', false);
		$refund_info = order_refund::get_refundorder_detail(array('refund_sn' => $refund_sn));
		
		if (empty($refund_info)) {
			return new ecjia_error('not_exists_info', '不存在的信息！');
		}
		
		//当前申请是否支持自选快递返回方式
		$return_shipping_range = $return_shipping_range = explode(',', $refund_info['return_shipping_range']);
		if (!in_array('express', $return_shipping_range)) {
			return new ecjia_error('return_shipping_range_error', '当前申请不支持自选快递返还方式！');
		}
		
		if (empty($shipping_name) || empty($shipping_sn)) {
			return new ecjia_error('express_info_error', '快递信息不能为空！');
		}
        //收件地址默认为店铺地址
		$store_info = RC_DB::table('store_franchisee')->where('store_id', $refund_info['store_id'])->selectRaw('merchants_name, responsible_person, city, district, street, address')->first();
		$store_recipients = $store_info['responsible_person'];
		$store_name = $store_info['merchants_name'];
		/*商家电话*/
		$store_service_phone = RC_DB::table('merchants_config')->where('store_id', $refund_info['store_id'])->where('code', 'shop_kf_mobile')->pluck('value');
		//店铺地址
		$store_address = ecjia_region::getRegionName($store_info['city']).ecjia_region::getRegionName($store_info['district']).ecjia_region::getRegionName($store_info['street']).$store_info['address'];
		//默认地址，收货人，联系方式
		if (empty($recipient_address)) {
			$recipient_address = $store_address;
		}
		if (empty($recipients)) {
			$recipients = $store_recipients;
		}
		if (empty($contact_phone)) {
			$contact_phone = $store_service_phone;
		}
		
        $express = array(
        		'return_way_code' 	=> 'express',
        		'return_way_name' 	=> '自选快递',
        		'recipient_address' => $recipient_address,
        		'recipients'		=> $recipients,
        		'contact_phone' 	=> $contact_phone,
        		'shipping_name'		=> $shipping_name,
        		'shipping_sn'		=> $shipping_sn
        );
        
        $express = serialize($express);
        $update_data = array('return_shipping_type' => 'express', 'return_time'	=> RC_Time::gmtime(), 'return_shipping_value' => $express, 'return_status' => 2);
       	RC_DB::table('refund_order')->where('refund_sn', $refund_sn)->update($update_data);
       	//订单状态log记录
       	$pra = array('order_status' => '返还退货商品', 'order_id' => $refund_info['order_id'], 'message' => '买家已返还退货商品！');
       	order_refund::order_status_log($pra);
        
        return array();
	}
}

// end