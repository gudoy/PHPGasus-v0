{extends file='specific/layout/page.tpl'}

{block name='mainContent'}
<section id="_404Block">
	<header>
		<h2>
			{t}401 Unauthorized{/t}
		</h2>
	</header>
	<div class="content">
		<p>
			{t}Sorry but the page you requested has restricted access.{/t}
		</p>
		<p>
			{t}Maybe:{/t}
		</p>
		<ul>
			<li>
				{t}you can contact us if you think you should have access to it{/t}
			</li>
			<li>
				{t}you misspelled the address if you typed it{/t}
			</li>
		</ul>
		<nav class="actions">
			<span class="or">
			{include file='common/blocks/actionBtn.tpl' href="mailto:{$smarty.const._APP_OWNER_CONTACT_MAIL}" label="{t}contact us{/t}"}
		</nav>
	</div>
</section>
{/block}