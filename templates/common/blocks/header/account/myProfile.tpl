{if $data.current.user}
<div id="myAccountNavBlock" class="accountNav myAccountNav accountBlock myAccountNavBlock hcard">
    <div class="header titleBlock item-lv1"><a class="accountActions" id="accountActions" href="#myAccountDetailsBlock"><h2 class="title"><span class="value">{t}account{/t}</span></h2></a></div>
    {include file='common/blocks/header/account/detail.tpl' user=$data.current.user} 
</div>
{/if}