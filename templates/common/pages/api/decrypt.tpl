<form action="" method="post">
	<fieldset>
		<legend>AES decrypt</legend>
		<div class="line">
			<label for="accessKeyId">Access Key id</label>
			<input type="tex" class="normal" name="accessKeyId" id="accessKeyId" value="{$smarty.post.accessKeyId}" />
		</div>
		<div class="line">
			<label for="stringToHandle">String</label>
			<input type="tex" class="normal" name="stringToHandle" id="stringToHandle" value="{$smarty.post.stringToHandle}" />
		</div>
		<button type="submit" name="forcePost" value="{$smarty.now}">{t}decrypt{/t}
	</fieldset>
</form>