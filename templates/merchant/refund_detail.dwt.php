<?php defined('IN_ECJIA') or exit('No permission resources.');?>
<!--{extends file="ecjia-merchant.dwt.php"} -->

<!-- {block name="footer"} -->
<script type="text/javascript">
	ecjia.merchant.mh_refund.refund_info();
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
				<p>退款编号：</p>
				<p>申请人：</p>
				<p>退款原因：</p>
				<p>退款金额：</p>
				<p>退款说明：</p>
				<p>上传凭证：</p>
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
           		<div class="goods-info">
           			<div class="goods-img">
	           			<img src="{$ecjia_main_static_url}img/ecjia_avatar.jpg">
		           	</div>
	           		<div class="goods-desc">
	           			 <p>法国青蛇果</p>
	           			 <p>¥&nbsp;28.00&nbsp;&nbsp;&nbsp;x1</p>
	           		</div>
           		</div>
           		<hr>
                <p>运费：¥&nbsp;0.00</p>
                <p>订单总额：¥&nbsp;57.60</p>
                <hr>
                <p>订单编号：8372814709 <span><a>查看更多</a></span></p>
                <hr>
                <p>收货人：送钱 <span><a>查看更多</a></span></p>
	        </div>
        </div>
	</div>
</div>
<!-- {/block} -->