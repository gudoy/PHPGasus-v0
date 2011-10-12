{block name='warningsBlock'}
{if count($data.warnings)}
<div id="warningsBlock" class="notificationsBlock warningsBlock">
	<ul class="warnings warningsList">
		{foreach $data.warnings as $warning}
		<li class="notification warning" id="warning{$warning.id}">{$warning.message|default:'unknown warning'}</li>
		{/foreach}
	</ul>
</div>
{/if}
{/block}