<?php defined('IN_ECJIA') or exit('No permission resources.');?>
<!--{extends file="ecjia-merchant.dwt.php"} -->

<!-- {block name="footer"} -->
<script type="text/javascript">
	ecjia.merchant.return_info.init();
</script>
<!-- {/block} -->

<!-- {block name="home-content"} -->
<!-- #BeginLibraryItem "/library/return_step.lbi" --><!-- #EndLibraryItem -->

<div class="page-header">
	<div class="pull-left">
		<h2><!-- {if $ur_here}{$ur_here}{/if} --></h2>
  	</div>
  	<div class="clearfix"></div>
</div>

<div class="row" id="home-content">		

	<div id="actionmodal" class="modal fade">
        <div class="modal-dialog" style="margin-top: 200px;">
            <div class="modal-content">
                <div class="modal-header">
                    <button data-dismiss="modal" class="close" type="button">×</button>
                    <h4 class="modal-title">选择返还方式</h4>
                </div>
                
                <div class="modal-body">
                	  <div class="success-msg"></div>
		              <div class="error-msg"></div>
                      <form class="form-horizontal" method="post" name="actionForm" id="actionForm" action='{url path="refund/merchant/merchant_check_return"}'>
						<div class="form-group refund-label">
							<div class="controls col-lg-9">
								<input name="return_shipping_range" id="home" value="home" type="checkbox"> 
								<label for="home"><strong>上门取货</strong></label><small>（由商家联系配送员上门取货）</small>
							</div>
						</div>
						
						<div class="form-group refund-label">
							<div class="controls col-lg-9">
								<input name="return_shipping_range" id="express" value="express" type="checkbox"> 
								<label for="express"><strong>自选快递</strong></label><small>（由用户自选第三方快递公司配送）</small>
								<div class="return_shipping_content">
									<p>收件人：{$return_shipping_content.staff_name} &nbsp;&nbsp;&nbsp;手机：{$return_shipping_content.staff_mobile}</p>
									<p>地址：{$return_shipping_content.address}</p>
								</div>
							</div>
						</div>
						
						<div class="form-group refund-label">
							<div class="controls col-lg-9">
								<input name="return_shipping_range" id="shop" value="shop" type="checkbox"> 
								<label for="shop"><strong>到店退货</strong></label><small>（由用户到门店线下退货）</small>
								<div class="return_shipping_content">
									<p>店铺名称：{$return_shipping_content.store_name} &nbsp;&nbsp;&nbsp;电话：{$return_shipping_content.shop_kf_mobile}</p>
									<p>地址：{$return_shipping_content.address}</p>
								</div>
							</div>
						</div>
						
                        <div class=" return-btn">
                              <button type="submit" id="note_btn" class="btn btn-primary ">确定</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
   	</div>
	   
    <div class="col-lg-8">
        <section class="panel panel-body">
            <h4>买家退货退款申请</h4>
			<div class="refund_content">
				<p>退款编号：{$refund_info.refund_sn}</p>
				<p>申请人：{$refund_info.user_name}</p>
				<p>退款原因：{if $refund_info.refund_reason eq 1}暂时不想购买了{elseif $refund_info.refund_reason eq 2}忘记使⽤优惠券{elseif $refund_info.refund_reason eq 3}商家缺货， 不想买了{elseif $refund_info.refund_reason eq 4}商家服务态度有问题{elseif $refund_info.refund_reason eq 5}商家长时间未发货{elseif $refund_info.refund_reason eq 6}信息填写有误， 重新购买{else}暂无原因{/if}</p>
				<p>退款金额：{$refund_info.money_paid}</p>
				<p>退款说明：{$refund_info.refund_content}</p>
				<p>上传凭证： 
					{if $refund_img_list}
					<!-- {foreach from=$refund_img_list item=list} -->
	                <img src="{RC_Upload::upload_url()}/{$list.file_path}">
	                <!-- {/foreach} -->
	                {else}
	            	<img src="{RC_Uri::admin_url('statics/images/nopic.png')}">
					{/if}
                </p>
			</div>
        </section>
        
        
        {if $refund_info.status eq '1' or $refund_info.status eq '11'}
	        <section class="panel panel-body">
				<h4>商家退货退款意见</h4>
				<div class="{if $range}return_mer_check{else}mer_check{/if}">
					<p>处理状态：{if $refund_info.status eq '1'}同意{elseif $refund_info.status eq '11'}不同意{/if}</p>
					<p>商家备注：{$action_mer_msg.action_note}</p>
					{if $range}
						<p>可用退货方式：{$range}</p>
					{/if}
					<p>操作人：{$action_mer_msg.action_user_name}</p>
					<p>处理时间：{$action_mer_msg.log_time}</p>
				</div>
	        </section>
        {else}
	        <section class="panel panel-body">
				<h4>商家退货退款操作</h4>
				 <div class="mer-content">
                     <h5 class="mer-title">操作备注：</h5>
                     <div class="mer-content-textarea">
                          <textarea class="form-control" id="action_note" name="action_note" ></textarea>
                     </div>
                 </div>
				 <div class="mer-btn">
				 	<a style="cursor: pointer;" class="btn btn-primary" href="#actionmodal" data-toggle="modal" id="modal">同意</a>
				 	
				 	<input type="hidden" id="refund_id" value="{$refund_id}"  />
					<a style="cursor: pointer;"  class="btn btn-primary change_status_disagree" data-href='{url path="refund/merchant/merchant_check_return"}' >
						不同意
					</a>
			     </div>
	        </section>
        {/if}
        
        <!-- 商家已发货 -->
        {if $refund_info.return_status eq '2'}
         	<section class="panel panel-body">
				<h4>商家确认收货操作</h4>
				 <div class="mer-content">
                     <h5 class="mer-title">操作备注：</h5>
                     <div class="mer-content-textarea">
                          <textarea class="form-control" id="action_note" name="action_note" ></textarea>
                     </div>
                 </div>
				 <div class="mer-btn">
				 	<input type="hidden" id="refund_id" value="{$refund_id}"  />
				 	<a style="cursor: pointer;"  class="btn btn-primary confirm_change_status" data-type='get' data-href='{url path="refund/merchant/merchant_confirm"}' >
						确认收货
					</a>
					
					<a style="cursor: pointer;"  class="btn btn-primary confirm_change_status" data-type='noget' data-href='{url path="refund/merchant/merchant_confirm"}' >
						未收到货
					</a>
			     </div>
	        </section>
	    {elseif $refund_info.return_status eq '3' or $refund_info.return_status eq '11'}
	    	 <section class="panel panel-body">
				<h4>商家确认收货意见</h4>
				<div class="mer_check">
					<p>处理状态：{if $refund_info.status eq '1'}同意{elseif $refund_info.status eq '11'}不同意{/if}</p>
					<p>商家备注：{$action_mer_msg.action_note}</p>
					<p>操作人：{$action_mer_msg.action_user_name}</p>
					<p>处理时间：{$action_mer_msg.log_time}</p>
				</div>
	        </section>    
        {/if}
        
        <!-- 平台已打款 -->
        {if $refund_info.refund_status eq '2'}
	        <section class="panel panel-body">
				<h4>商城平台退款审核</h4>
				<div class="mer_check">
					<p>平台确认：已退款</p>
					<p>平台备注：{$action_admin_msg.action_note}</p>
					<p>操作人：{$action_admin_msg.action_user_name}</p>
					<p>处理时间：{$action_admin_msg.log_time}</p>
				</div>
	        </section>
		        
	        <section class="panel panel-body">
				<h4>商城平台退款详情</h4>
				<div class="adm_check">
					<p>退款方式：{$payrecord_info.back_pay_name}</p>
					<p>应退款金额：- ¥ {$payrecord_info.back_money_paid}</p>
					<p>积分：- {$payrecord_info.back_integral}</p>
					<p>实际退款金额：- ¥ {$payrecord_info.back_money_paid}</p>
					<p>退款时间：{$payrecord_info.back_time}</p>
				</div>
	       </section>
        {/if}
    </div>
    
    <div class="col-lg-4">
        <div class="panel panel-body">
            <h4>商品相关信息</h4>
           	<div class="goods-content">
           		<!-- {foreach from=$goods_list item=list} -->
           		<div class="goods-info">
           			<div class="goods-img">
	           			<img src="{$list.image}">
		           	</div>
	           		<div class="goods-desc">
	           			 <p>{$list.goods_name}</p>
	           			 <p>¥&nbsp;{$list.shop_price}&nbsp;&nbsp;&nbsp;x1</p>
	           		</div>
           		</div>
           		 <!-- {/foreach} -->
           		<hr>
                <p>运费：¥&nbsp;{$order_info.shipping_fee}</p>
                <p>订单总额：¥&nbsp;{$order_info.money_paid}</p>
                <hr>
                <p>订单编号：{$order_info.order_sn} <span><a id="order-info" href="javascript:;">查看更多</a></span></p>
                <div class="order-info" style="display: none;">
	                <p>支付方式：{$order_info.pay_name}</p>
	                <p>下单时间：{$order_info.add_time}</p>
	                <p>付款时间：{$order_info.pay_time}</p>
                </div>
                <hr>
                <p>收货人：{$order_info.consignee}<span><a id="address-info" href="javascript:;">查看更多</a></span></p>
                <div class="address-info" style="display: none;">
	                <p>收货地址：{$order_info.province}{$order_info.city}{$order_info.district}{$order_info.street}</p>
	                <p>联系电话：{$order_info.mobile}</p>
                </div>
	        </div>
        </div>
	</div>
</div>
<!-- {/block} -->