{block name='adminDataModelCodeBlock'}
<div class="block adminBlock adminDataModelBlock adminDataModelGroupsBlock" id="adminDataModelGroupsBlock">
	<header class="titleBlock">
		<h3 class="title">
			<span>datamodel groups</span>	
		</h3>
	</header>
	<div class="content">
		<fieldset>
			{if $data._extras.dataModel.groups}
			<ul class="groups">
			{foreach $data._extras.dataModel.groups as $name => $props}
				<li><span class="value">{$props.displayName|default:$name}</span></li>
			{/foreach}
			</ul>
			{/if}
		</fieldset>
	</div>
</div>
{/block}