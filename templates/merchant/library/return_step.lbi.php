<?php defined('IN_ECJIA') or exit('No permission resources.');?> 
<div class="panel panel-body">
	<div class="return-time-base">
		<ul>
			<li class="step-first">
				<div class="{if $refund_info.status eq '0'}step-cur{else}step-done{/if}">
					<div class="step-no">{if $refund_info.status eq '0'}1{/if}</div>
					<div class="m_t5">买家申请退货退款<br>{if $refund_info.add_time}{$refund_info.add_time}{/if}</div>	
				</div>
			</li>
			
			<li>
				<div class="{if $refund_info.status eq '1' and $refund_info.return_shipping_range neq ''}step-done{elseif $refund_info.status eq '11'}step-cur{/if}">
					<div class="step-no">{if $refund_info.return_shipping_range eq ''}2{/if}</div>
					<div class="m_t5">商家处理退款申请<br>{if $action_mer_msg.log_time}{$action_mer_msg.log_time}{/if}</div>
				</div>
			</li>
			
			<li>
				<div class="{if $refund_info.return_shipping_range neq '' and $refund_info.return_status eq '2'}step-done{elseif $refund_info.return_shipping_range neq ''}step-cur{/if}">
					<div class="step-no">{if $refund_info.return_status neq '2'}3{/if}</div>
					<div class="m_t5">买家退货给商家<br>{if $refund_info.return_time}{$refund_info.return_time}{/if}<br></div>
				</div>
			</li>
			
			<li>
				<div class="{if $refund_info.return_status eq '2'}step-cur{/if}">
					<div class="step-no">{if $refund_info.refund_status neq '2'}4{/if}</div>
					<div class="m_t5">商家确认收货<br></div>
				</div>
			</li>

			<li class="step-last">
				<div class="{if $refund_info.refund_status eq '2'}step-cur{/if}">
					<div class="step-no">5</div>
					<div class="m_t5">平台审核、退款完成<br>{if $refund_info.refund_time}{$refund_info.refund_time}{/if}</div>
				</div>
			</li>
		</ul>
	</div>
</div>
