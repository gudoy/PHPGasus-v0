{block name='mailContent'}
Hello {$data.user.first_name}

Welcome to {$smarty.const._APP_TITLE}!

We just need you to click on the following link to complete your registration :

{$smarty.const._URL_ACCOUNT_CONFIRMATION}?key={$data.user.activation_key}{if $data.user.prefered_lang}&lang={$data.user.prefered_lang}{/if}

Thanks,

the {$smarty.const._APP_TITLE} Team
{$smarty.const._URL}

{/block}