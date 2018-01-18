<?php defined('IN_ECJIA') or exit('No permission resources.');?>
<!--{extends file="ecjia-merchant.dwt.php"} -->

<!-- {block name="footer"} -->
<script type="text/javascript">
	ecjia.merchant.refund_info.init();
</script>
<!-- {/block} -->

<!-- {block name="home-content"} -->
<div class="page-header">
	<div class="pull-left">
		<h2><!-- {if $ur_here}{$ur_here}{/if} --></h2>
  	</div>
  	<div class="clearfix"></div>
</div>

<div class="row" id="home-content">
    <div class="col-lg-8">
        <section class="panel panel-body">
            <h4>买家退款申请</h4>
			<div class="refund_content">
				<p>退款编号：{$refund_info.refund_sn}</p>
				<p>申请人：{$refund_info.user_name}</p>
				<p>退款原因：{if $refund_info.refund_reason eq 1}暂时不想购买了{elseif $refund_info.refund_reason eq 2}忘记使⽤优惠券{elseif $refund_info.refund_reason eq 3}商家缺货， 不想买了{elseif $refund_info.refund_reason eq 4}商家服务态度有问题{elseif $refund_info.refund_reason eq 5}商家⻓时间未发货{elseif $refund_info.refund_reason eq 6}信息填写有误， 重新购买{else}暂无原因{/if}</p>
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
        
        <section class="panel panel-body">
			<h4>商家退款操作</h4>
			<form class="form-horizontal" action="{$from_action}" method="post" name="theForm">
				 <div class="mer-content">
                     <h5 class="mer-title">操作备注：</h5>
                     <div class="mer-content-textarea">
                          <textarea class="form-control" id="mer_content" name="mer_content" ></textarea>
                     </div>
                 </div>
				 <div class="mer-btn">
			   		<input class="btn btn-info" type="submit" value="同意"/>
			   		<input class="btn btn-info" type="submit" value="不同意"/>
			     </div>
			</form>
        </section>
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
	           			 <p>¥&nbsp;{$list.goods_price}&nbsp;&nbsp;&nbsp;x{$list.goods_number}</p>
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
	                <p>收货地址：{$order_info.district}{$order_info.street}</p>
	                <p>联系电话：{$order_info.mobile}</p>
                </div>
	        </div>
        </div>
	</div>
</div>
<!-- {/block} -->