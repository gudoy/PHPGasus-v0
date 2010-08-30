{extends file='specific/layout/page.tpl'}

{block name='pageContent'}
<div class="pageContent" id="{$data.view.name|default:'noName'}PageContent">

	<div id="apiBox">
			
		<div class="exposedAPIsBlock box grid_11" id="exposedAPIsBlock">
			
			<h2>{t}Exposed APIs{/t}</h2>
			
			{block name='exposedApiContent'}
			<div class="resourceAPIBlock first">
				<h3>Couponcodes</h3>
				<ul>
					<li>
						<h4>
							<span class="method">POST</span>
							<span class="resourceURI">/couponcodes/?accessKeyId={ $apiClientId }&amp;requestSign={ sha1($encodedURI) }</span>
						</h4>
						<div class="prop outputData">
							<span class="key">Output data{t}:{/t}</span>
							<span class="val">
								data of the created couponcode
							</span>
						</div>
						<div class="prop possibleErrors">
							<span class="key">Possible errors{t}:{/t}</span>
							<dl class="val">
								<dt>401 Unauthorized</dt>
							</dl>
						</div>
						<div class="prop summary">
							<span class="key">{t}Description{/t}{t}:{/t}</span>
							<p class="val summary">create a new couponcode an return its data</p>
						</div>
						<div class="prop sampleURI">
							<span class="key">{t}Sample{/t}{t}:{/t}</span>
							<a class="val" href="{$smarty.const._URL_API}couponcodes/?output=json&amp;accesKeyId=1&amp;requestSign=encodedResquestSignSaltGoesHere">
								{$smarty.const._URL_API}couponcodes/?output=json&amp;accesKeyId=1&amp;requestSign=encodedResquestSignSaltGoesHere
							</a>
						</div>
					</li>
				</ul>
			</div>
			
			<div class="resourceAPIBlock">
				<h3>Purchases</h3>
				<ul>
					<li>
						<h4>
							<span class="method">POST</span>
							<span class="resourceURI">/productpacks/purchases/{ $productpackId }</span>
							<span class="entityBody">+ POST data</span>
						</h4>
						<div class="prop expectedInputData">
							<span class="key">Expected data{t}:{/t}</span>
							<dl class="val">
								<dt>application_id</dt>
								<dt>device_id</dt>
								<dt>couponcode</dt>
								<dt>[local]</dt>
							</dl>
						</div>
						<div class="prop outputData">
							<span class="key">Output data{t}:{/t}</span>
							<span class="val">
								purchase object containing (used_couponcode, success_media_uri)
							</span>
						</div>
						<div class="prop possibleErrors">
							<span class="key">Possible errors{t}:{/t}</span>
							<dl class="val">
								<dt>400 Bad Request</dt>
								<dd>Missing required productpack ID</dd>
								<dt>400 Bad Request</dt>
								<dd>Missing required device ID</dd>
								<dt>400 Bad Request</dt>
								<dd>Missing application ID</dd>
								<dt>400 Bad Request</dt>
								<dd>Missing required productpack ID</dd>
								<dt>204 No Content</dt>
								<dd>Productpack not found</dd>
								<dt>417 Expectation Failed</dt>
								<dd>Invalid couponcode</dd>
								<dt>417 Expectation Failed</dt>
								<dd>Invalid apple transaction receipt</dd>
								<dt>409 Conflict</dt>
								<dd>Already purchased content</dd>
							</dl>
						</div>
						<div class="prop summary">
							<span class="key">{t}Description{/t}{t}:{/t}</span>
							<p class="val summary">Purchase the product pack</p>
						</div>
						<div class="prop sampleURI">
							<span class="key">{t}Sample{/t}{t}:{/t}</span>
							<a class="val" href="{$smarty.const._URL_API}productpacks/purchases/1?method=create">
								{$smarty.const._URL_API}productpacks/purchases/1
							</a>
						</div>
					</li>
				</ul>
			</div>

			<div class="resourceAPIBlock">
				<h3>Purchased contents</h3>
				<ul>
					<li>
						<h4>
							<span class="method">GET</span>
							<span class="resourceURI">/purchasedcontents/{ $device_id }</span>
						</h4>
						<div class="prop outputData">
							<span class="key">Output data{t}:{/t}</span>
							<span class="val">
								list of purchased contents by the passed device id.
							</span>
						</div>
						<div class="prop possibleErrors">
							<span class="key">Possible errors{t}:{/t}</span>
							<dl class="val">
								<dt>400 Bad Request</dt>
								<dd>Missing required device ID</dd>
								<dt>204 No Content</dt>
								<dd>No purchased content for this device id</dd>
							</dl>
						</div>
						<div class="prop summary">
							<span class="key">{t}Description{/t}{t}:{/t}</span>
							<p class="val summary">list the purchased contents by the passed device id</p>
						</div>
						<div class="prop sampleURI">
							<span class="key">{t}Sample{/t}{t}:{/t}</span>
							<a class="val" href="{$smarty.const._URL_API}purchasedcontents/EFDDC644-8A65-5F20-80FC-F3EAB657437B">
								{$smarty.const._URL_API}purchasedcontents/EFDDC644-8A65-5F20-80FC-F3EAB657437B
							</a>
						</div>
					</li>
					<li>
						<h4>
							<span class="method">GET</span>
							<span class="resourceURI">/purchasedcontents/{ $device_id }/{ $product_packs_ids } (separated by comas)</span>
						</h4>
						<div class="prop outputData">
							<span class="key">Output data{t}:{/t}</span>
							<span class="val">
								list of purchased contents by the passed device id. Filtered by product pack id if passed
							</span>
						</div>
						<div class="prop possibleErrors">
							<span class="key">Possible errors{t}:{/t}</span>
							<dl class="val">
								<dt>400 Bad Request</dt>
								<dd>Missing required device ID</dd>
								<dt>204 No Content</dt>
								<dd>No purchased content for this device id</dd>
							</dl>
						</div>
						<div class="prop summary">
							<span class="key">{t}Description{/t}{t}:{/t}</span>
							<p class="val summary">list the purchased contents by the passed device id</p>
						</div>
						<div class="prop sampleURI">
							<span class="key">{t}Sample{/t}{t}:{/t}</span>
							<a class="val" href="{$smarty.const._URL_API}purchasedcontents/3be4d71e808a1eee0710a2bd201651580c16a1ae/1,5">
								{$smarty.const._URL_API}purchasedcontents/3be4d71e808a1eee0710a2bd201651580c16a1ae/1,5
							</a>
						</div>
					</li>
				</ul>
			</div>
			
			<div class="resourceAPIBlock">
				<h3>Push registrations</h3>
				<ul>
					<li>
						<h4>
							<span class="method">POST</span>
							<span class="resourceURI">/pushregistrations/</span>
							<span class="entityBody">+ POST data</span>
						</h4>
						<div class="prop expectedInputData">
							<span class="key">Expected data{t}:{/t}</span>
							<dl class="val">
								<dt>applications_id</dt>
								<dt>device_id</dt>
								<dt>token</dt>
								<dt>[language]</dt>
							</dl>
						</div>
						<div class="prop outputData">
							<span class="key">Output data{t}:{/t}</span>
							<span class="val">
								data of the created pushapps token
							</span>
						</div>
						<div class="prop possibleErrors">
							<span class="key">Possible errors{t}:{/t}</span>
							<dl class="val">
								<dt>400 Bad Request</dt>
								<dd>Missing application ID</dd>
								<dt>400 Bad Request</dt>
								<dd>Missing required device ID</dd>
								<dt>400 Bad Request</dt>
								<dd>Missing required token</dd>
							</dl>
						</div>
						<div class="prop summary">
							<span class="key">{t}Description{/t}{t}:{/t}</span>
							<p class="val summary">register the passed device to the push messages service for the passed application id</p>
						</div>
						<div class="prop sampleURI">
							<span class="key">{t}Sample{/t}{t}:{/t}</span>
							<a class="val" href="{$smarty.const._URL_API}pushregistrations/?method=create">
								{$smarty.const._URL_API}pushregistrations/
							</a>
						</div>
					</li>
				</ul>
			</div>
			
			<div class="resourceAPIBlock">
				<h3>Shared contents</h3>
				<ul>
					<li>
						<h4>
							<span class="method">GET</span>
							<span class="resourceURI">sharedcontents/remainingrights/{ $device_id }/</span>
						</h4>
						<div class="prop outputData">
							<span class="key">Output data{t}:{/t}</span>
							<span class="val">
								object containing the number of remaining sharing rights
							</span>
						</div>
						<div class="prop possibleErrors">
							<span class="key">Possible errors{t}:{/t}</span>
							<dl class="val">
								<dt>400 Bad Request</dt>
								<dd>Missing required device ID</dd>
								<dt>204 No Content</dt>
								<dd>No shared contents for this device ID</dd>							
							</dl>
						</div>
						<div class="prop summary">
							<span class="key">{t}Description{/t}{t}:{/t}</span>
							<p class="val summary">Sum the number of remaining sharing rights for the passed device id</p>
						</div>
						<div class="prop sampleURI">
							<span class="key">{t}Sample{/t}{t}:{/t}</span>
							<a class="val" href="{$smarty.const._URL_API}sharedcontents/remainingrights/ef9e8bd132477d32beaeff5d4d10c5274a75ca63">
								{$smarty.const._URL_API}sharedcontents/remainingrights/ef9e8bd132477d32beaeff5d4d10c5274a75ca63
							</a>
						</div>
					</li>
					<li>
						<h4>
							<span class="method">PUT</span>
							<span class="resourceURI">sharedcontents/notification/{ recipient_email }</span>
							<span class="entityBody">+ PUT data</span>
						</h4>
						<div class="prop expectedInputData">
							<span class="key">Expected data{t}:{/t}</span>
							<dl class="val">
								<dt>msg_object</dt>
								<dt>msg_text</dt>
								<dt>[couponcode]</dt>
								<dt>[msg_lang]</dt>
							</dl>
						</div>
						<div class="prop possibleErrors">
							<span class="key">Possible errors{t}:{/t}</span>
							<dl class="val">
								<dt>400 Bad Request</dt>
								<dd>Missing required {ldelim}paramName{rdelim} param</dd>						
							</dl>
						</div>
						<div class="prop summary">
							<span class="key">{t}Description{/t}{t}:{/t}</span>
							<p class="val summary">Send the sharing notification mail to the passed send_to</p>
						</div>
						<div class="prop sampleURI">
							<span class="key">{t}Sample{/t}{t}:{/t}</span>
							<a class="val" href="{$smarty.const._URL_API}sharedcontents/notification/toto@toto.com">
								{$smarty.const._URL_API}sharedcontents/notification/toto@toto.com
							</a>
						</div>
					</li>
					<li>
						<h4>
							<span class="method">POST</span>
							<span class="resourceURI">/sharedcontents/{ $product_pack_id }</span>
							<span class="entityBody">+ POST data</span>
						</h4>
						<div class="prop expectedInputData">
							<span class="key">Expected data{t}:{/t}</span>
							<dl class="val">
								<dt>sharedby_device</dt>
								<dt>[sent_couponcode]</dt>
								<dt>[recipient_email]</dt>
								<dt>[sent_to_plateform]</dt>
							</dl>
						</div>
						<div class="prop outputData">
							<span class="key">Output data{t}:{/t}</span>
							<span class="val">
								nothing
							</span>
						</div>
						<div class="prop possibleErrors">
							<span class="key">Possible errors{t}:{/t}</span>
							<dl class="val">
								<dt>400 Bad Request</dt>
								<dd>Missing required device ID</dd>
								<dt>400 Bad Request</dt>
								<dd>Missing required productpack ID</dd>
								<dt>417 Expectation Failed</dt>
								<dd>No remaining sharing rights left</dd>
							</dl>
						</div>
						<div class="prop summary">
							<span class="key">{t}Description{/t}{t}:{/t}</span>
							<p class="val summary">Store a new sharing's data for the passed product pack id</p>
						</div>
						<div class="prop sampleURI">
							<span class="key">{t}Sample{/t}{t}:{/t}</span>
							<a class="val" href="{$smarty.const._URL_API}sharedcontents/1?method=create">
								{$smarty.const._URL_API}sharedcontents/1
							</a>
						</div>
					</li>
					{*
					<li>
						<h4>
							<span class="method">GET</span>
							<span class="resourceURI">/sharedcontents/{ldelim}$device_id{rdelim}</span>
						</h4>
						<div class="prop outputData">
							<span class="key">Output data{t}:{/t}</span>
							<span class="val">
								list of shared contents for the passed device id.
							</span>
						</div>
						<div class="prop possibleErrors">
							<span class="key">Possible errors{t}:{/t}</span>
							<dl class="val">
								<dt>400 Bad Request</dt>
								<dd>Missing required device ID</dd>
								<dt>204 No Content</dt>
								<dd>No shared content for this device id</dd>
							</dl>
						</div>
						<div class="prop summary">
							<span class="key">{t}Description{/t}{t}:{/t}</span>
							<p class="val summary">list the contents shared by the passed device id</p>
						</div>
						<div class="prop sampleURI">
							<span class="key">{t}Sample{/t}{t}:{/t}</span>
							<a class="val" href="{$smarty.const._URL_API}sharedcontents/EFDDC644-8A65-5F20-80FC-F3EAB657437B">
								{$smarty.const._URL_API}sharedcontents/EFDDC644-8A65-5F20-80FC-F3EAB657437B 
							</a>
						</div>
					</li>
					*}
				</ul>
			</div>
			{/block}
			
		</div>
		
		{block name='commonParamsContent'}
		<div class="grid_4">
			
			<div class="commonParams commonApiheaders box">
				<h2>{t}Accepted request headers{/t}</h2>
				<dl class="paramsList">
					<dt class="name">Accept</dt>
					<dd>
						<span class="summary">Set the expected output format of the data</span>
						<ul class="acceptedValues">
							<li>text/html application/xhtml+xml</li>
							<li>text/xml, application/xml</li>
							<li>application/json</li>
							<li>text/yaml</li>
							<li>application/plist+xml</li>
						</ul>
					</dd>
				</dl>
			</div>
			
			<div class="commonParams commonAPIparams box">
				<h2>{t}Accepted URI params{/t}</h2>
				<dl class="paramsList">
					<dt class="name">output</dt>
					<dd>
						<span class="summary">Set the expected output format of the data (overload Accept header)</span>
						<ul class="acceptedValues">
							<li>xhtml</li>
							<li>xml</li>
							<li>json</li>
							<li>yaml</li>
							<li>plist</li>
						</ul>
					</dd>
					<dt class="name">offset</dt>
					<dd>
						<span class="summary">Offset from which you want to get data</span>
						<span class="acceptedValues">any numeric</span>
					</dd>
					<dt class="name">limit</dt>
					<dd>
						<span class="summary">Maximum number of resources you want to get</span>
						<span class="acceptedValues">any numeric</span>
					</dd>
					<dt class="name">sortBy</dt>
					<dd>
						<span class="summary">Name of the field to sort resources by</span>
						<span class="acceptedValues">any existing field name</span>
					</dd>
					<dt class="name">orderBy</dt>
					<dd>
						<span class="summary">Direction of the sorting operation</span>
						<span class="acceptedValues">ASC or DESC</span>
					</dd>
				</dl>
			</div>
			
		</div>
		{/block}
	</div>

</div>
{/block}