{block name='mailContent'}
Hello {$data.users.first_name}

Click on the following to be able to reset your password

{$smarty.const._URL_ACCOUNT_PASSWORD_RESET}/{$data.users.id}?key={$data.users.password_reset_key}

Thanks,

the {$smarty.const._APP_TITLE} Team
{$smarty.const._URL}

{/block}