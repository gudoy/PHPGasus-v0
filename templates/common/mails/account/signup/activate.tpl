{block name='mailContent'}
Hello {$data.user.first_name}

Welcome to {$smarty.const._APP_TITLE}!

We just need you to click on the following link to complete your registration :

{$smarty.const._URL_ACCOUNT_CONFIRM}?key={$data.user.activation_key}
{/block}