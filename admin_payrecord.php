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
 * 交易退款管理
 * @author songqianqian
 */
class admin_payrecord extends ecjia_admin {
	
	public function __construct() {
		parent::__construct();
		
		/* 加载全局 js/css */
		RC_Script::enqueue_script('jquery-validate');
		RC_Script::enqueue_script('jquery-form');
		RC_Script::enqueue_script('smoke');
		RC_Script::enqueue_script('bootstrap-editable.min', RC_Uri::admin_url('statics/lib/x-editable/bootstrap-editable/js/bootstrap-editable.min.js'), array(), false, false);
		RC_Style::enqueue_style('bootstrap-editable',RC_Uri::admin_url('statics/lib/x-editable/bootstrap-editable/css/bootstrap-editable.css'), array(), false, false);
		RC_Style::enqueue_style('chosen');
		RC_Style::enqueue_style('uniform-aristo');

		RC_Script::enqueue_script('jquery-uniform');
		RC_Script::enqueue_script('jquery-chosen');

		RC_Script::enqueue_script('jquery.toggle.buttons', RC_Uri::admin_url('statics/lib/toggle_buttons/jquery.toggle.buttons.js'));
		RC_Style::enqueue_style('bootstrap-toggle-buttons', RC_Uri::admin_url('statics/lib/toggle_buttons/bootstrap-toggle-buttons.css'));

		//时间控件
		RC_Style::enqueue_style('datepicker', RC_Uri::admin_url('statics/lib/datepicker/datepicker.css'));
		RC_Style::enqueue_style('datetimepicker', RC_Uri::admin_url('statics/lib/datepicker/bootstrap-datetimepicker.min.css'));
		RC_Script::enqueue_script('bootstrap-datepicker', RC_Uri::admin_url('statics/lib/datepicker/bootstrap-datepicker.min.js'));
		RC_Script::enqueue_script('bootstrap-datetimepicker', RC_Uri::admin_url('statics/lib/datepicker/bootstrap-datetimepicker.js'));

		RC_Script::enqueue_script('admin_payrecord', RC_App::apps_url('statics/js/admin_payrecord.js', __FILE__));
		RC_Style::enqueue_style('admin_payrecord', RC_App::apps_url('statics/css/admin_payrecord.css', __FILE__));
		
		ecjia_screen::get_current_screen()->add_nav_here(new admin_nav_here('交易退款', RC_Uri::url('refund/admin_payrecord/init')));
	}
	
	/**
	 * 交易退款
	 */
	public function init() {
		$this->admin_priv('payrecord_manage');
		
		ecjia_screen::get_current_screen()->remove_last_nav_here();
		ecjia_screen::get_current_screen()->add_nav_here(new admin_nav_here('交易退款'));
		$this->assign('ur_here', '交易退款');
		
		$data = $this->payrecord_list();
		if(empty($data['filter']['back_type'])) {
			$data['filter']['back_type'] = 'wait';
		}
		$this->assign('filter', $data['filter']);
		$this->assign('data', $data);
		
		$this->assign('search_action', RC_Uri::url('refund/admin_payrecord/init'));
		
		$this->display('payrecord_list.dwt');
	}
	
	/**
	 * 交易退款查看详情
	 */
	public function detail() {
		$this->admin_priv('payrecord_manage');
		
		ecjia_screen::get_current_screen()->add_nav_here(new admin_nav_here('退款详情'));
		$this->assign('ur_here', '退款详情');
		
		$refund_id = intval($_GET['refund_id']);
		$this->assign('refund_id', $refund_id);
		
		//退款订单信息
		$refund_info = RC_DB::table('refund_order')->where('refund_id', $refund_id)->first();
		$this->assign('refund_info', $refund_info);
		
		//获取用户退货退款原因
		$reason_list = $this->get_reason_list();
		$this->assign('reason_list', $reason_list);
		
		//退款上传凭证素材
		$refund_img_list = RC_DB::table('term_attachment')->where('object_id', $refund_info['refund_id'])->where('object_app', 'ecjia.refund')->where('object_group','refund')->select('file_path')->get();
		$this->assign('refund_img_list', $refund_img_list);
		
		//退款有关下单信息
		$order_info = RC_DB::table('order_info')->where('order_id', $refund_info['order_id'])->select('shipping_fee','order_sn','money_paid','pay_name','pay_time','add_time','consignee','province','city','district','street','mobile')->first();
		$order_info['province']	= ecjia_region::getRegionName($order_info['province']);
		$order_info['city']     = ecjia_region::getRegionName($order_info['city']);
		$order_info['district'] = ecjia_region::getRegionName($order_info['district']);
		$order_info['street']   = ecjia_region::getRegionName($order_info['street']);
		if ($order_info['add_time']) {
			$order_info['add_time'] = RC_Time::local_date(ecjia::config('time_format'), $order_info['add_time']);
		}
		if ($order_info['pay_time']) {
			$order_info['pay_time'] = RC_Time::local_date(ecjia::config('time_format'), $order_info['pay_time']);
		}
		$this->assign('order_info', $order_info);
		
		
		//打款表信息
		$payrecord_info = RC_DB::table('refund_payrecord')->where('refund_id', $refund_id)->first();
		if ($payrecord_info['add_time']) {
			$payrecord_info['add_time'] = RC_Time::local_date(ecjia::config('time_format'), $payrecord_info['add_time']);
		}
		if ($payrecord_info['back_time']) {
			$payrecord_info['back_time'] = RC_Time::local_date(ecjia::config('time_format'), $payrecord_info['back_time']);
		}
		$this->assign('payrecord_info', $payrecord_info);
		
		$this->assign('form_action', RC_Uri::url('refund/admin_payrecord/update'));
		
		
		$this->assign('original_img', RC_App::apps_url('statics/images/original_pic.png', __FILE__));
		$this->assign('surplus_img', RC_App::apps_url('statics/images/surplus_pic.png', __FILE__));
		$this->assign('selected_img', RC_App::apps_url('statics/images/selected.png', __FILE__));
		
		$this->display('payrecord_detail.dwt');
		
	}
	
	
	/**
	 * 处理打款逻辑
	 */
	public function update() {
		$this->admin_priv('payrecord_manage');
		$id = intval($_POST['id']);
		$refund_id    = intval($_POST['refund_id']);
		$refund_type  = trim($_POST['refund_type']);
		$back_type 	  = $_POST['back_type'];
		$back_money_paid = $_POST['back_money_paid'];
		$back_integral 	 = $_POST['back_integral'];
		$back_content    = trim($_POST['back_content']);
		
		if ($refund_type) {
			$return_status = 0;
		} else {
			$return_status = 3;
		}
		//用户表和资金变动表变动
		$user_id = RC_DB::TABLE('refund_order')->where('refund_id', $refund_id)->pluck('user_id');
		if ($back_type == 'surplus') {//退回余额  （消费积分和金额）
			$action_note = '退款金额已退回余额'.$back_money_paid.'元，退回积分为：'.$back_integral;
			//更新帐户变动记录 
			$account_log = array (
				'user_id'		=> $user_id,
				'user_money'	=> $back_money_paid,
				'pay_points'	=> $back_integral,
				'change_time'	=> RC_Time::gmtime(),
				'change_desc'	=> $back_content,
				'change_type'	=> ACT_REFUND
			);
			RC_DB::table('account_log')->insertGetId($account_log);
			
			//更新用户表
			$step = $back_money_paid." ,pay_points = pay_points + ('$back_integral')";
			RC_DB::table('users')->where('user_id', $user_id)->increment('user_money', $step);
		} 
// 		else {//TODO
// 			$action_note = '退款金额已原路退回'.$back_money_paid.'元，退回积分为：'.$back_integral;
// 			return $this->showmessage('抱歉！目前还不支持原路退回', ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
// 		}
		
		//更新打款表
		$data = array(
			'back_type'			=>	$back_type,
			'back_time'			=>	RC_Time::gmtime(),
			'back_content'		=>	$back_content,	
			'action_user_id'	=>	$_SESSION['admin_id'],	
			'action_user_name'	=>	$_SESSION['admin_name']
		);
		RC_DB::table('refund_payrecord')->where('id', $id)->update($data);
		
		//更新退款订单表
		$data = array(
			'refund_status'	=> 2,
			'refund_time'	=> RC_Time::gmtime(),
		);
		RC_DB::table('refund_order')->where('refund_id', $refund_id)->update($data);
		
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
		
		//录入退款订单状态变动日志表
		$data = array(
			'refund_id' 	=>  $refund_id,
			'status'		=>  '退款到账',
			'message'		=>  '',
			'add_time'		=>  RC_Time::gmtime(),
		);
		RC_DB::table('refund_status_log')->insertGetId($data);
		
		
		//录入普通订单状态变动日志表
		$order_id = RC_DB::TABLE('refund_order')->where('refund_id', $refund_id)->pluck('order_id');
		OrderStatusLog::return_confirm_receive(array('order_id' => $order_id, 'back_money' => $back_money_paid));
		
		//短信告知用户退款退货成功 
// 		$user_info = RC_DB::table('users')->where('user_id', $user_id)->select('user_name', 'pay_points', 'user_money', 'mobile_phone')->first();
// 		if (!empty($user_info['mobile_phone'])) {
// 			$options = array(
// 				'mobile' => $user_info['mobile_phone'],
// 				'event'	 => 'sms_refund_change',
// 				'value'  =>array(
// 					'user_name' 	=> $user_info['user_name'],
// 					'amount' 		=> $back_money_paid,
// 					'user_money' 	=> $user_info['user_money'],
// 					'point' 		=> $back_integral,
// 					'pay_points' 	=> $user_info['pay_points'],
// 					'service_phone' => ecjia::config('service_phone'),
// 				),
// 			);
// 			RC_Api::api('sms', 'send_event_sms', $options);
// 		}

		return $this->showmessage('退款操作成功', ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS, array('pjaxurl' => RC_Uri::url('refund/admin_payrecord/detail', array('refund_id' => $refund_id))));
	}

	/**
	 * 获取交易退款列表
	 */
	private function payrecord_list() {
		$db_refund_view = RC_DB::table('refund_payrecord as rp')
		->leftJoin('store_franchisee as s', RC_DB::raw('s.store_id'), '=', RC_DB::raw('rp.store_id'));
		
		$filter['start_date']= $_GET['start_date'];
		$filter['end_date']  = $_GET['end_date'];
		if (!empty($filter['start_date']) && !empty($filter['end_date'])) {
			$filter['start_date']	= RC_Time::local_strtotime($filter['start_date']);
			$filter['end_date']		= RC_Time::local_strtotime($filter['end_date']);
			$db_refund_view->where('add_time', '>=', $filter['start_date']);
			$db_refund_view->where('add_time', '<', $filter['end_date'] + 86400);
		}
		
		$filter['keywords']  = trim($_GET['keywords']);
		if ($filter['keywords']) {
			$db_refund_view ->whereRaw('(refund_sn  like  "%'.mysql_like_quote($filter['keywords']).'%"  or s.merchants_name like "%'.mysql_like_quote($filter['keywords']).'%")');
		}
		
		$refund_type = $_GET['refund_type'];
		if (!empty($refund_type)){
			$db_refund_view ->where('refund_type', $refund_type);
		}
	
		$filter['back_type'] = trim($_GET['back_type']);
		$refund_count = $db_refund_view->select(
				RC_DB::raw('SUM(IF(back_time = 0, 1, 0)) as wait'),
				RC_DB::raw('SUM(IF(back_time > 0, 1, 0)) as have'))->first();
		
		if ($filter['back_type'] == 'wait' || $filter['back_type'] == '') {
			$db_refund_view->whereNull(RC_DB::raw('back_type'));
		}
		
		if ($filter['back_type'] == 'have') {
			$db_refund_view->whereNotNull(RC_DB::raw('back_type'));
		} 
		$count = $db_refund_view->count();
		$page = new ecjia_page($count, 10, 5);
		$data = $db_refund_view
		->select('id','order_sn','refund_sn','refund_id','refund_type','back_type','back_money_paid','back_time','add_time',RC_DB::raw('s.merchants_name'))
		->orderby('id', 'DESC')
		->take(10)
		->skip($page->start_id-1)
		->get();
	
		$list = array();
		if (!empty($data)) {
			foreach ($data as $row) {
				$row['back_time']  = RC_Time::local_date('Y-m-d H:i:s', $row['back_time']);
				$row['add_time']  = RC_Time::local_date('Y-m-d H:i:s', $row['add_time']);
				$list[] = $row;
			}
		}
		return array('list' => $list, 'filter' => $filter, 'page' => $page->show(5), 'desc' => $page->page_desc(), 'count' => $refund_count);
	}
	

	/**
	 * 获取退货原因列表
	 */
	private function get_reason_list(){
		$reason_list = array(
			'1'	=> '暂时不想购买了',
			'2' => '忘记使用优惠券',
			'3' => '商家缺货，不想买了',
			'4' => '商家服务态度有问题',
			'5' => '商家长时间未发货',
			'6' => '信息填写有误，重新购买',

			'11' => '商品质量问题',
			'12' => '发错货',
			'13' => '缺斤少两',
			'14' => '外表损伤（包装，商品等）',
			'15' => '未在时效内送达',
			'16' => '误购'
		);
		return $reason_list;
	}
}

//end