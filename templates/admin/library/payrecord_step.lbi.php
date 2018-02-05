<?php defined('IN_ECJIA') or exit('No permission resources.');?> 
<div class="panel panel-body">
	<div class="payrecord-time-base">
		<ul>
			<li class="step-first">
				<div class="{if $refund_info.refund_status eq '2'}step-done{else}step-cur{/if}">
					<div class="step-no">{if $refund_info.refund_status neq '2'}1{/if}</div>
					<div class="m_t5">商家提交退款申请<br>{if $payrecord_info.add_time}{$payrecord_info.add_time}{/if}</div>	
				</div>
			</li>

			<li class="step-last">
				<div class="">
					<div class="step-no">3</div>
					<div class="m_t5">平台审核、退款完成<br>{if $refund_info.refund_time}{$refund_info.refund_time}{/if}</div>
				</div>
			</li>
		</ul>
	</div>
</div>
