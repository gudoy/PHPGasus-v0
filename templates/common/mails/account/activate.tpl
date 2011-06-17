{block name='mailContent'}
Hello {$data.users.first_name}

Welcome to {$smarty.const._APP_TITLE}!

We just need you to click on the following link to complete your registration :

{$smarty.const._URL_ACCOUNT_CONFIRMATION}?key={$data.users.activation_key}

Thanks,

the {$smarty.const._APP_TITLE} Team
{$smarty.const._URL}

{/block}