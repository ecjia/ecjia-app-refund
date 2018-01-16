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
        <section class="panel">
            <div class="panel-body">
	            <h4>买家退款申请</h4>
				<div class="refund_content">
					<p>退款编号：</p>
					<p>申请人：</p>
					<p>退款原因：</p>
					<p>退款金额：</p>
					<p>退款说明：</p>
					<p>上传凭证：</p>
				</div>
            </div>
        </section>
        
        <section class="panel">
			<div class="panel-body">
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
			</div>    
        </section>
    </div>
    
    
    <div class="col-lg-4">
        <div class="panel panel-body">
           
        </div>
	</div>
</div>
<!-- {/block} -->