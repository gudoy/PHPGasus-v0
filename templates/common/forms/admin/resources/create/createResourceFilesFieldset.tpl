<fieldset id="adminCreateResourceFilesFieldset">
	<legend><span>{t}Create resources files{/t}</span></legend>
	<div class="line">
		<div class="labelBlock">
			<label for="createController">controller</label>
		</div>
		<div class="fieldBlock">
			<input type="checkbox" name="createController" id="createController" {if !isset($smarty.post.createController) || $smarty.post.createController}checked="checked"{/if} value="1" />
		</div>
	</div>
	<div class="line">
		<div class="labelBlock">
			<label for="createModel">model</label>
		</div>
		<div class="fieldBlock">
			<input type="checkbox" name="createModel" id="createModel" {if !isset($smarty.post.createModel) || $smarty.post.createModel}checked="checked"{/if} value="1" />
		</div>
	</div>
	<div class="line">
		<div class="labelBlock">
			<label for="createView">view</label>
		</div>
		<div class="fieldBlock">
			<input type="checkbox" name="createView" id="createView" {if $smarty.post.createView}checked="checked"{/if} value="1" />
		</div>
	</div>
	<div class="line">
		<div class="labelBlock">
			<label for="createAdminView">admin view</label>
		</div>
		<div class="fieldBlock">
			<input type="checkbox" name="createAdminView" id="createAdminView" {if !isset($smarty.post.createAdminView) || $smarty.post.createAdminView}checked="checked"{/if} value="1" />
		</div>
	</div>
	<div class="line">
		<div class="labelBlock">
			<label for="createApiView">api view (TODO)</label>
		</div>
		<div class="fieldBlock">
			<input type="checkbox" name="createApiView" id="createApiView" {if $smarty.post.createApiView}checked="checked"{/if} disabled="disabled" value="1" />
		</div>
	</div>
	<div class="line">
		<div class="labelBlock">
			<label for="createTemplates">templates (TODO)</label>
		</div>
		<div class="fieldBlock">
			<input type="checkbox" name="createTemplates" id="createTemplates" {if $smarty.post.createTemplates}checked="checked"{/if} disabled="disabled" value="1" />
		</div>
	</div>
</fieldset>