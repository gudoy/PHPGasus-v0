{if $data.platform.name === 'iphone'}
	{assign var='isIphone' value=true}
{/if}
<div class="line noLabelBlock {if $isIphone}ninja{/if} {if $lastline}lastline{/if}">
	<div class="fieldBlock">
		<input type="checkbox" class="multi sized" id="userHasIphone" name="userHasIphone" {if $smarty.post.userHasIphone || $isIphone}checked="checked"{/if} />
		<label class="span" for="userHasIphone">{t}I have an iPhone/iPod Touch{/t}</label>
	</div>
</div>