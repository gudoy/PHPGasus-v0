<form action="" method="post">
	<fieldset>
		<legend>Sha1</legend>
		<div class="line">
			<label for="stringToHandle">String</label>
			<input type="tex" class="normal" name="stringToHandle" id="stringToHandle" value="{$smarty.post.stringToHandle}" />
		</div>
		<button type="submit" name="forcePost" value="{$smarty.now}">{t}encrypt{/t}
	</fieldset>
</form>