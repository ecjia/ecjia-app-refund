<?php defined('IN_ECJIA') or exit('No permission resources.');?>
<!-- {extends file="ecjia.dwt.php"} -->

<!-- {block name="footer"} -->
<script type="text/javascript">
    ecjia.admin.payrecord_info.init();
</script>

<!-- {/block} -->

<!-- {block name="main_content"} -->
<div>
	<h3 class="heading">
		<!-- {if $ur_here}{$ur_here}{/if} -->
	</h3>
</div>

<!-- #BeginLibraryItem "/library/payrecord_step.lbi" --><!-- #EndLibraryItem -->
<form id="form-privilege" class="form-horizontal" name="theForm" action="{$form_action}" method="post" >
	<fieldset>
		<div class="row-fluid editpage-rightbar edit-page">
			<div class="left-bar">
				<h3>买家退款申请</h3>
				<div class="control-group">
					<label class="control-label">退款编号：</label>
					<div class="controls l_h30">{$refund_info.refund_sn}</div>
				</div>
				
				<div class="control-group">
					<label class="control-label">申请人：</label>
					<div class="controls l_h30">{$refund_info.user_name}</div>
				</div>
				
				<div class="control-group">
					<label class="control-label">退款原因：</label>
					<div class="controls l_h30">
					<!-- {foreach from=$reason_list key=key item=val} -->
	 				{if $key eq $refund_info.refund_reason}{$val}{/if}
					<!-- {/foreach} -->
				</div>
				
				<div class="control-group">
					<label class="control-label">退款金额：</label>
					<div class="controls l_h30">{$refund_info.money_paid}</div>
				</div>
				
				<div class="control-group">
					<label class="control-label">退款说明：</label>
					<div class="controls l_h30">{if $refund_info.refund_content}{$refund_info.refund_content}{else}暂无{/if}</div>
				</div>
				
				<div class="control-group formSep">
					<label class="control-label">上传凭证：</label>
					<div class="controls l_h30 refund_content">
						{if $refund_img_list}
						<!-- {foreach from=$refund_img_list item=list} -->
			            <img src="{RC_Upload::upload_url()}/{$list.file_path}">
			            <!-- {/foreach} -->
			            {else}
			        	暂无
						{/if}
					</div>
				</div>
				
				{if !$payrecord_info.back_content}
					<h3>退款操作</h3>
					<div class="control-group">
						<label class="control-label">退款金额：</label>
						<div class="controls l_h30 ecjiafc-red"><strong>¥ {$payrecord_info.back_money_paid} 元</strong></div>
					</div>
					
					<div class="control-group">
						<label class="control-label">退回积分：</label>
						<div class="controls l_h30 ecjiafc-red"><strong>{$payrecord_info.back_integral}</strong></div>
					</div>
					
					<div class="control-group">
						<label class="control-label">退款方式：</label>
						<div class="controls back-logo-wrap">
						     <ul>
						         <li class="back-logo active" data-type="surplus">
						             <img src="{$surplus_img}">
						             <img class="back-logo-select" src="{$selected_img}">
						         </li>
						     </ul>
						     <input name="back_type" value="surplus" type="hidden">
						</div>
					</div>
					
					<div class="control-group">
						<label class="control-label">操作备注：</label>
						<div class="controls">
							<textarea name="back_content" id="back_content" class="span10" placeholder="请输入退款备注"></textarea>
							<span class="input-must">{lang key='system::system.require_field'}</span>
							<span class="help-block">输入退款说明等内容，此项为必填项</span>
						</div>
					</div>
	
					<div class="control-group">
						<div class="controls">
						    <button class="btn btn-gebo" type="submit">退款</button>
							<input type="hidden" name="id" value="{$payrecord_info.id}" />
							<input type="hidden" name="refund_id" value="{$payrecord_info.refund_id}" />
							<input type="hidden" name="refund_type" value="{$payrecord_info.refund_type}" />
							<input type="hidden" name="back_money_paid" value="{$payrecord_info.back_money_paid}" />
							<input type="hidden" name="back_integral" value="{$payrecord_info.back_integral}" />
						</div>
					</div>
				{else}
					<h3>平台退款详情</h3>
					<div class="control-group">
						<label class="control-label">平台确认：</label>
						<div class="controls l_h30">已退款</div>
					</div>
					
					<div class="control-group">
						<label class="control-label">退款方式：</label>
						<div class="controls l_h30">{if $payrecord_info.back_type eq 'original'}原路退回{else}退回余额{/if}</div>
					</div>
					
					<div class="control-group">
						<label class="control-label">应退款金额：</label>
						<div class="controls l_h30">- ¥ {$payrecord_info.back_money_paid} 元</div>
					</div>
					
					<div class="control-group">
						<label class="control-label">积分：</label>
						<div class="controls l_h30">- {$payrecord_info.back_integral}</div>
					</div>
					
					<div class="control-group">
						<label class="control-label">实际退款金额：</label>
						<div class="controls l_h30">- ¥ {$payrecord_info.back_money_paid} 元</div>
					</div>
					
					<div class="control-group">
						<label class="control-label">退款备注：</label>
						<div class="controls l_h30">{$payrecord_info.back_content}</div>
					</div>
					
					<div class="control-group">
						<label class="control-label">退款时间：</label>
						<div class="controls l_h30">{$payrecord_info.back_time}</div>
					</div>
					
					<div class="control-group">
						<label class="control-label">操作人：</label>
						<div class="controls l_h30">{$payrecord_info.action_user_name}</div>
					</div>
				{/if}
			</div>

			<div class="right-bar move-mod">
				<div class="foldable-list move-mod-group" >
					<div class="accordion-group">
						<div class="accordion-heading">
							<a class="accordion-toggle collapsed move-mod-head" data-toggle="collapse" data-target="#refund_goods_content">
								<strong>订单基本信息</strong>
							</a>
						</div>
						<div class="accordion-body in collapse reply_admin_list" id="refund_goods_content">
							<div class="accordion-inner">
							 	<div class="goods-content">
					           		<p>订单编号：{$order_info.order_sn}</p>
					           		<hr>
					                <p>运费：¥&nbsp;{$order_info.shipping_fee}</p>
					                <p>订单总额：¥&nbsp;57.60（退款：¥&nbsp;57.60）</p>
					                <p>支付方式：{$order_info.pay_name}</p>
					                <p>下单时间：{$order_info.add_time}</p>
					                <p>付款时间：{$order_info.pay_time}</p>
					                <a id="order-info" href='{url path="refund/admin/refund_detail" args="refund_id={$refund_info.refund_id}"}'>点此处查看退款单详细信息 >></a>
						        </div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</fieldset>
</form>

<!-- {/block} -->