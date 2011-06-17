<!-- Start Filter Block -->
<div class="block filterBlock inlineFormBlock" id="filter{$resourceName|capitalize}Block">
	<form class="commonForm" id="filter{$resourceName|capitalize}" action="" method="get">
		<fieldset>
			<legend class="displayed"><span class="value">{t}Filter results{/t}{t}:{/t}</span></legend>
			<div class="line">
				<div class="labelBlock">
					<label class="span" for="filterBy">{t}by{/t}{t}:{/t}</label>
				</div>
				<div class="fieldBlock">
					<select class="sized" id="filterBy" name="by">
						{foreach name='tableFields' from=$data.dataModel[$resourceName] key='fieldName' item='field'}
						<option value="{$fieldName}" {if $smarty.get.by === $fieldName}selected="selected"{/if}>{$fieldName}</option>
						{/foreach}
					</select>
				</div>
			</div>
			<div class="line">
				<div class="labelBlock">
					<label class="span" for="filterOperation">{t}matching relation{/t}{t}:{/t}</label>
				</div>
				<div class="fieldBlock">
					<select class="sized" id="filterOperation" name="operation">
						<option value="exactValues" {if $smarty.get.operation === 'exactValues'}selected="selected"{/if}>{t}exact value(s){/t}</option>
						<option value="valueContains" {if $smarty.get.operation === 'valueContains'}selected="selected"{/if}>{t}contains{/t}</option>
						<option value="valueNotContains" {if $smarty.get.operation === 'valueNotContains'}selected="selected"{/if}>{t}does NOT contains{/t}</option>
						<option value="valueIsGreater" {if $smarty.get.operation === 'valueIsGreater'}selected="selected"{/if}>{t}is greater than{/t}</option>
						<option value="valueIsGreaterOrEqual" {if $smarty.get.operation === 'valueIsGreaterOrEqual'}selected="selected"{/if}>{t}is greater or equal than/to{/t}</option>
						<option value="valueIsLower" {if $smarty.get.operation === 'valueIsLower'}selected="selected"{/if}>{t}is lower than{/t}</option>
						<option value="valueIsLowerOrEqual" {if $smarty.get.operation === 'valueIsLowerOrEqual'}selected="selected"{/if}>{t}is lower or equal than/to{/t}</option>
						<option value="valueStartsBy" {if $smarty.get.operation === 'valueStartsBy'}selected="selected"{/if}>{t}starts by{/t}</option>
						<option value="valueEndsBy" {if $smarty.get.operation === 'valueEndsBy'}selected="selected"{/if}>{t}ends by{/t}</option>
						<option value="valueIsNot" {if $smarty.get.operation === 'valueIsNot'}selected="selected"{/if}>{t}is not{/t}</option>
					</select>
				</div>
			</div>
			<div class="line">
				<div class="labelBlock">
					<label class="span" for="filterValues">{t}value(s){/t}{t}:{/t}</label>
				</div>
				<div class="fieldBlock">
					<input class="sized" type="text" id="filterValues" name="values" value="{$smarty.get.values}" />
				</div>
			</div>
			<div class="line buttonsLine">
				{include file='common/blocks/actionBtn.tpl' mode='button' id='filterBtn' type='submit' label='Filter'|gettext}
			</div>
			{if $smarty.get.offset}
			<input type="hidden" name="offset" value="{$smarty.get.offset}" />
			{/if}
			{if $smarty.get.offset}
			<input type="hidden" name="limit" value="{$smarty.get.limit}" />
			{/if}
		</fieldset>
	</form>
</div>
<!-- End Filter Block -->