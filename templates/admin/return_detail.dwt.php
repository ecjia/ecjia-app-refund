<?php defined('IN_ECJIA') or exit('No permission resources.');?>
<!-- {extends file="ecjia.dwt.php"} -->

<!-- {block name="footer"} -->
<script type="text/javascript">
    ecjia.admin.return_info.init();
</script>
<!-- {/block} -->

<!-- {block name="main_content"} -->
<div>
	<h3 class="heading">
		<!-- {if $ur_here}{$ur_here}{/if} -->
	</h3>
</div>

<!-- #BeginLibraryItem "/library/return_step.lbi" --><!-- #EndLibraryItem -->

<div class="row-fluid editpage-rightbar">
	<div class="left-bar move-mod">
		<div class="foldable-list move-mod-group" >
			<div class="accordion-group">
				<div class="accordion-heading">
					<a class="accordion-toggle collapsed move-mod-head" data-toggle="collapse" data-target="#refund_content">
						<strong>买家退货退款申请</strong>
					</a>
				</div>
				<div class="accordion-body in collapse" id="refund_content">
					<div class="refund_content">
						<p>退款编号：{$refund_info.refund_sn}</p>
						<p>申请人：{$refund_info.user_name}</p>
						<p>退款原因：
						<!-- {foreach from=$reason_list key=key item=val} -->
		 				{if $key eq $refund_info.refund_reason}{$val}{/if}
						<!-- {/foreach} -->
						</p>
						<p>退款金额：{$refund_total_amount}</p>
						<p>退款说明：{if $refund_info.refund_content}{$refund_info.refund_content}{else}暂无{/if}</p>
						<p>上传凭证： 
							{if $refund_img_list}
							<!-- {foreach from=$refund_img_list item=list} -->
				                <a class="up-img no-underline" href="{RC_Upload::upload_url()}/{$list.file_path}" title="{$list.file_name}">
									<img src="{RC_Upload::upload_url()}/{$list.file_path}"/>
								</a>
			                <!-- {/foreach} -->
			                {else}
			            	暂无
							{/if}
		                </p>
					</div>
				</div>
			</div>
			
			{if $refund_info.status eq '1' or $refund_info.status eq '11'}
				<div class="accordion-group">
					<div class="accordion-heading">
						<a class="accordion-toggle collapsed move-mod-head" data-toggle="collapse" data-target="#mer_content">
							<strong>商家退货退款意见</strong>
						</a>
					</div>
					<div class="accordion-body in collapse" id="mer_content">
						<div class="refund_content">
							<p>处理状态：{if $refund_info.status eq '1'}同意{elseif $refund_info.status eq '11'}不同意{/if}</p>
							<p>商家备注：{$action_mer_msg_return.action_note}</p>
							{if $range}
								<p>可用退货方式：{$range}</p>
							{/if}
							<p>操作人：{$action_mer_msg_return.action_user_name}</p>
							<p>处理时间：{$action_mer_msg_return.log_time}</p>
						</div>
					</div>
				</div>
			{/if}
			
			{if $refund_info.return_status gt '1'}
				<div class="accordion-group">
					<div class="accordion-heading">
						<a class="accordion-toggle collapsed move-mod-head" data-toggle="collapse" data-target="#return_shipping">
							<strong>买家退货信息</strong>
						</a>
					</div>
					<div class="accordion-body in collapse" id="return_shipping">
						<div class="refund_content">
							{if $return_shipping_value.return_way_code eq 'home'}
								<p>退货方式：{$return_shipping_value.return_way_name}</p>
								<p>取件地址：{$return_shipping_value.pickup_address}</p>
								<p>期望取件时间：{$return_shipping_value.expect_pickup_time}</p>
								<p>联系人：{$return_shipping_value.contact_name}</p>
								<p>联系电话：{$return_shipping_value.contact_phone}</p>
							{elseif $return_shipping_value.return_way_code eq 'express'}
								<p>退货方式：{$return_shipping_value.return_way_name}</p>
								<p>收件人：{$return_shipping_value.recipients}</p>
								<p>联系方式：{$return_shipping_value.contact_phone}</p>
								<p>收件地址：{$return_shipping_value.recipient_address}</p>
								<p>快递名称：{$return_shipping_value.shipping_name}</p>
								<p>快递单号：{$return_shipping_value.shipping_sn}</p>
							{else}
								<p>退货方式：{$return_shipping_value.return_way_name}</p>
								<p>店铺名称：{$return_shipping_value.store_name}</p>
								<p>联系方式：{$return_shipping_value.contact_phone}</p>
								<p>店铺地址：{$return_shipping_value.store_address}</p>
							{/if}
						</div>
					</div>
				</div>
			{/if}
			
			<!-- 商家已发货 -->
		    {if $refund_info.return_status eq '3' or $refund_info.return_status eq '11'}
		    	<div class="accordion-group">
					<div class="accordion-heading">
						<a class="accordion-toggle collapsed move-mod-head" data-toggle="collapse" data-target="#mer_content">
							<strong>商家确认收货意见</strong>
						</a>
					</div>
					<div class="accordion-body in collapse" id="mer_content">
						<div class="refund_content">
							<p>处理状态：{if $refund_info.return_status eq '3'}确认收货{elseif $refund_info.return_status eq '11'}未收到货{/if}</p>
							<p>商家备注：{$action_mer_msg_confirm.action_note}</p>
							<p>操作人：{$action_mer_msg_confirm.action_user_name}</p>
							<p>处理时间：{$action_mer_msg_confirm.log_time}</p>
						</div>
					</div>
				</div>  
				{if $refund_info.return_status eq '3' and $refund_info.refund_status eq '1'}
					<div style="margin-top: 20px;">
						退款操作：<a href='{url path="refund/admin_payrecord/detail" args="refund_id={$refund_info.refund_id}"}' class="data-pjax"><button class="btn btn-gebo" type="button">去退款</button>  </a>     
					</div>
				{/if}
	        {/if}
			
			<!-- 平台已打款 -->			
			{if $refund_info.refund_status eq '2' }
				<div class="accordion-group">
					<div class="accordion-heading">
						<a class="accordion-toggle collapsed move-mod-head" data-toggle="collapse" data-target="#admin_content">
							<strong>商城平台退款审核</strong>
						</a>
					</div>
					<div class="accordion-body in collapse" id="admin_content">
						<div class="refund_content">
							<p>平台确认：已退款</p>
							<p>平台备注：{$action_admin_msg.action_note}</p>
							<p>操作人：{$action_admin_msg.action_user_name}</p>
							<p>处理时间：{$action_admin_msg.log_time}</p>
						</div>
					</div>
				</div>
				
				<div class="accordion-group">
					<div class="accordion-heading">
						<a class="accordion-toggle collapsed move-mod-head" data-toggle="collapse" data-target="#admin_content">
							<strong>商城平台退款详情</strong>
						</a>
					</div>
					<div class="accordion-body in collapse" id="admin_content">
						<div class="refund_content">
							<p>退款方式：{if $payrecord_info.back_type eq 'original'}原路退回{else}退回余额{/if}</p>
							<p>应退款金额：- ¥ {$payrecord_info.back_money_paid}</p>
							<p>积分：- {$payrecord_info.back_integral}</p>
							<p>实际退款金额：- ¥ {$payrecord_info.back_money_paid}</p>
							<p>退款时间：{$payrecord_info.back_time}</p>
						</div>
					</div>
				</div>
			{/if}
		</div>
	</div>
	
	<div class="right-bar move-mod">
		<div class="foldable-list move-mod-group edit-page" >
			<div class="accordion-group">
				<div class="accordion-heading">
					<a class="accordion-toggle collapsed move-mod-head" data-toggle="collapse" data-target="#refund_mer_content">
						<strong>店铺信息</strong>
					</a>
				</div>
				<div class="accordion-body in in_visable collapse" id="refund_mer_content">
					<div class="accordion-inner">
					   <div class="merchant_content">
							<div class="list-top">
								<img src="{if $mer_info.img}{RC_Upload::upload_url()}/{$mer_info.img}{else}{RC_Uri::admin_url('statics/images/nopic.png')}{/if}"><span>{$mer_info.merchants_name}</span>
							</div>
							<div class="list-mid">
								<p><font class="ecjiafc-red">{$mer_info.count.refund_count}</font><br>仅退款</p>
								<p><font class="ecjiafc-red">{$mer_info.count.return_count}</font><br>退款退货</p>
							</div>
							
							<div class="list-bot">
								<div><label>营业时间：</label>{$mer_info.shop_trade_time.start}-{$mer_info.shop_trade_time.end}</div>
								<div><label>商家电话：</label>{$mer_info.shop_kf_mobile}</div>
								<div><label>商家地址：</label>{$mer_info.address}</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="foldable-list move-mod-group" >
			<div class="accordion-group">
				<div class="accordion-heading">
					<a class="accordion-toggle collapsed move-mod-head" data-toggle="collapse" data-target="#refund_goods_content">
						<strong>已收货商品</strong>
					</a>
				</div>
				<div class="accordion-body in collapse reply_admin_list" id="refund_goods_content">
					<div class="accordion-inner">
					 	<div class="goods-content">
			           		<!-- {foreach from=$goods_list item=list} -->
			           		<div class="goods-info">
			           			<div class="goods-img">
				           			<img src="{$list.image}">
					           	</div>
				           		<div class="goods-desc">
				           			 <p>{$list.goods_name}</p>
				           			 <p>{$list.goods_price}&nbsp;&nbsp;&nbsp;x{$list.goods_number}</p>
				           		</div>
			           		</div>
			           		<!-- {/foreach} -->
			           		<hr>
			                <p>运费：{$order_info.shipping_fee}</p>
			               	<p>订单总额：{$order_amount}（退款：{$refund_total_amount}）</p>
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
		</div>
		
		<div class="foldable-list move-mod-group" >
			<div class="accordion-group">
				<div class="accordion-heading">
					<a class="accordion-toggle collapsed move-mod-head" data-toggle="collapse" data-target="#refund_goods_content2">
						<strong>申请退货商品</strong>
					</a>
				</div>
				<div class="accordion-body in collapse reply_admin_list" id="refund_goods_content2">
					<div class="accordion-inner">
					 	<div class="goods-content">
			           		<!-- {foreach from=$refund_list item=list} -->
			           		<div class="goods-info">
			           			<div class="goods-img">
				           			<img src="{$list.image}">
					           	</div>
				           		<div class="goods-desc">
				           			 <p>{$list.goods_name}</p>
				           			 <p>{$list.goods_price}&nbsp;&nbsp;&nbsp;x{$list.send_number}</p>
				           		</div>
			           		</div>
			           		<!-- {/foreach} -->
				        </div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- {/block} -->