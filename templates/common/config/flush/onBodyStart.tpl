{strip}
{if $smarty.const._FLUSH_BUFFER_EARLY}
{php}
    //str_pad('',20000);
    ob_flush(); 
    flush();
{/php}
{/if}
{/strip}