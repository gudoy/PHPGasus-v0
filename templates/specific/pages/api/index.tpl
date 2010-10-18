{extends file='common/pages/api/index.tpl'}


{block name='exposedApiContent'}
<div class="resourceAPIBlock first">
	<h3>wishlists</h3>
	<ul>
		<li>
			<h4>
				<span class="method">GET</span>
				<span class="resourceURI">/wishlists/{ $wishlist_key }</span>
			</h4>
			<div class="prop outputData info">
				<span class="key">{t}return{/t}{t}:{/t}</span>
				<span class="val">
					wishlist object (extended with products (extended with comments)).
					<br/>
					If the wishlist has been paired with another one, return a merge of the two.
				</span>
			</div>
			<div class="prop successOutput success">
				<span class="key">success{t}:{/t}</span>
				<dl class="val">
					<dt>200 OK</dt>
				</dl>
			</div>
			<div class="prop possibleErrors error">
				<span class="key">{t}errors{/t}{t}:{/t}</span>
				<dl class="val">
					<dt>400 Bad Request</dt>
					<dd>Missing required param(s)</dd>
					<dt>204 No Content</dt>
					<dd>Wishlist not found</dd>
				</dl>
			</div>
			<div class="prop sampleURI">
				<span class="key">{t}sample{/t}{t}:{/t}</span>
				<a class="val" href="{$smarty.const._URL_API}wishlists/f4k3.json">
					{$smarty.const._URL_API}wishlists/f4k3
				</a>
			</div>
		</li>
		<li>
			<h4>
				<span class="method">POST</span>
				<span class="resourceURI">/wishlists/</span>  + POST data
			</h4>
			<div class="prop expectedInputData">
				<span class="key">{t}expected{/t}{t}:{/t}</span>
				<div class="val">
					<p class="raw">
						You need to provide at least one of those fields.
					</p>
					<dl>
						<dt>device_id</dt>
						<dt>user_firstname</dt>
					</dl>
				</div>
			</div>
			<div class="prop outputData info">
				<span class="key">{t}return{/t}{t}:{/t}</span>
				<span class="val">
					wishlist object
				</span>
			</div>
			<div class="prop successOutput success">
				<span class="key">success{t}:{/t}</span>
				<dl class="val">
					<dt>201 Created</dt>
				</dl>
			</div>
			<div class="prop possibleErrors error">
				<span class="key">{t}errors{/t}{t}:{/t}</span>
				<dl class="val">
					<dt>417 Expectation Failed</dt>
					<dd>Missing required param(s)</dd>
				</dl>
			</div>
			<div class="prop sampleURI">
				<span class="key">{t}sample{/t}{t}:{/t}</span>
				<a class="val" href="{$smarty.const._URL_API}wishlists/f4k3?method=update">
					{$smarty.const._URL_API}wishlists/f4k3
				</a>
			</div>
		</li>
		<li>
			<h4>
				<span class="method">PUT</span>
				<span class="resourceURI">/wishlists/{ $wishlist_key }</span>  + PUT data
			</h4>
			<div class="prop expectedInputData">
				<span class="key">{t}expected{/t}{t}:{/t}</span>
				<div class="val">
					<p class="raw">
						You need to provide at least one field.
					</p>
				</div>
			</div>
			<div class="prop outputData info">
				<span class="key">{t}return{/t}{t}:{/t}</span>
				<span class="val">
					wishlist object
				</span>
			</div>
			<div class="prop successOutput success">
				<span class="key">success{t}:{/t}</span>
				<dl class="val">
					<dt>200 OK</dt>
				</dl>
			</div>
			<div class="prop possibleErrors error">
				<span class="key">Possible errors{t}:{/t}</span>
				<dl class="val">
					<dt>400 Bad Request</dt>
					<dd>Missing required param(s)</dd>
					<dt>204 No Content</dt>
					<dd>Wishlist not found</dd>
				</dl>
			</div>
			<div class="prop sampleURI">
				<span class="key">{t}Sample{/t}{t}:{/t}</span>
				<a class="val" href="{$smarty.const._URL_API}wishlists/f4k3?method=update">
					{$smarty.const._URL_API}wishlists/f4k3
				</a>
			</div>
		</li>
	</ul>
</div>

<div class="resourceAPIBlock">
	<h3>wishlistscouples</h3>
	<ul>
		<li>
			<h4>
				<span class="method">POST</span>
				<span class="resourceURI">/wishlistscouples/</span>  + POST data
			</h4>
			<div class="prop expectedInputData">
				<span class="key">{t}expected{/t}{t}:{/t}</span>
				<div class="val">
					<dl>
						<dt>first_wishlist_id or first_wishlist_key</dt>
						<dt>second_wishlist_id or second_wishlist_key or second_wishlist_user_firstname</dt>
					</dl>
				</div>
			</div>
			<div class="prop outputData info">
				<span class="key">{t}return{/t}{t}:{/t}</span>
				<span class="val">
					wishlistscouples
				</span>
			</div>
			<div class="prop successOutput success">
				<span class="key">success{t}:{/t}</span>
				<dl class="val">
					<dt>201 Created</dt>
				</dl>
			</div>
			<div class="prop possibleErrors error">
				<span class="key">{t}errors{/t}{t}:{/t}</span>
				<dl class="val">
					<dt>404 Not Found</dt>
					<dd>One or more resource(s) could not be found</dd>
					<dt>409 Conflict</dt>
					<dd>Already existing resource</dd>
					<dt>417 Expectation Failed</dt>
					<dd>Missing required param(s)</dd>
				</dl>
			</div>
			<div class="prop sampleURI">
				<span class="key">{t}sample{/t}{t}:{/t}</span>
				<a class="val" href="{$smarty.const._URL_API}wishlistscouples/?method=create">
					{$smarty.const._URL_API}wishlistscouples
				</a>
			</div>
		</li>
	</ul>
</div>
{/block}