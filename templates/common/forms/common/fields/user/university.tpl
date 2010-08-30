<div class="line {if $lastline}lastline{/if}">
	<div class="labelBlock">
		<label for="userUniversity">{t}University{/t} <span class="required">*</span></label>
	</div>
	<div class="fieldBlock">
		<select class="normal" name="userUniversity" id="userUniversity">
			<option {if !isset($smary.post.userUniversity)}selected="selected"{/if}>{t}[Select]{/t}</option>
			{include file='forms/common/universities.tpl'}
		</select>
		<div class="hidden" id="preciseUniversityNameLine">
			<span class="label multi preciseUniversityName">{t}University{/t} ({t}name{/t}){t}:{/t}</span>
			<input type="text" class="normal" id="userUniversityNameOther" name="userUniversityNameOther" value="{$smarty.post.userUniversityNameOther}" />
		</div>
	</div>
</div>