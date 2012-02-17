{extends file='specific/layout/page.tpl'}

{block name='mainContent'}
<section id="_404Block">
	<header>
		<h2>
			{t}Whoooops, 404 Page not found{/t}
		</h2>
	</header>
	<div class="content">
		<p>
			{t}Sorry but the page you are looking for could not be found.{/t}
		</p>
		<p>
			{t}Maybe:{/t}
		</p>
		<ul>
			<li>
				{t}this page has moved or no longer exist{/t}
			</li>
			<li>
				{t}you misspelled the address if you typed it{/t}
			</li>
		</ul>
		<nav class="actions">
			{include file='common/blocks/actionBtn.tpl' href=$smarty.const._URL label="{t}get back to home{/t}"}
			<span class="or">
			{include file='common/blocks/actionBtn.tpl' href="mailto:{$smarty.const._APP}" label="{t}contact us{/t}"}
		</nav>
	</div>
</section>
{/block}