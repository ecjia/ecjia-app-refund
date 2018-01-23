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
				<div class="{if $refund_info.refund_status eq '1' or $refund_info.status eq '11'}step-cur{elseif $refund_info.status gt '0'}step-done{/if}">
					<div class="step-no">{if $refund_info.refund_status neq '2'}2{/if}</div>
					<div class="m_t5">商家处理退款申请<br>{if $action_info.log_time}{$action_info.log_time}{/if}</div>
				</div>
			</li>
			
			<li>
				<div class="">
					<div class="step-no">3</div>
					<div class="m_t5">买家退货给商家<br></div>
				</div>
			</li>
			
			<li>
				<div class="">
					<div class="step-no">4</div>
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
