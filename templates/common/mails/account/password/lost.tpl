{block name='mailContent'}
Hello {$data.user.first_name}

Click on the following link to be able to reset your password

{$smarty.const._URL_ACCOUNT_PASSWORD_RESET}/{$data.user.id}?key={$data.user.password_reset_key}{if $data.user.prefered_lang}&lang={$data.user.prefered_lang}{/if}

Thanks,

the {$smarty.const._APP_TITLE} Team
{$smarty.const._URL}

{/block}