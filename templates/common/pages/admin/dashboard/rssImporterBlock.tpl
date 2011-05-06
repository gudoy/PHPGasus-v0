{block name='mainContent'}
<div class="block box grid_4 rssImporterBlock" id="rssImporterBlock">
	<div class="titleBlock">
		<h2>RSS Importer</h2>
	</div>
	<div class="dataBlock">
		{if $data.success}
		<p class="notification success">
			{t}The feed has been succesfully imported{/t}
		</p>
		{else}
		<p>
			{t}Import RSS items from{/t}
			<br/>
			{$smarty.const._URL_ARC_RSS_FEED}
		</p>
		<form action="" method="post">
			<input type="hidden" name="rssimporter" value="1" />
			{include file='common/formFields/buttons/validate.tpl' btnLabel='Import'}
		</form>
		{/if}
	</div>
</div>
{/block}