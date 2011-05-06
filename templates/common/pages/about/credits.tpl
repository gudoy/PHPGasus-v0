{extends file='specific/layout/page.tpl'}

{block name='mainContent'}
<div id="staffCreditsBlock" class="block creditsBlock staffBlock">
    <h3>{t}staff{/t}</h3>
    <dl class="staff">
    
        <dt>{t}project manager{/t}</dt>
        <dd>Olivier Saier</dd>
        <dd>sébastien bottalico</dd>
        
        <dt>{t}ergonomist{/t}</dt>
        <dd>clément mondary</dd>
        
        <dt>{t}design{/t}</dt>
        <dd>carmen sandmann</dd>
        <dd>guyllaume doyer</dd>
        
        <dt>{t}developer{/t}</dt>
        <dd>guyllaume doyer</dd>
    </dl>
</div>

<div id="technosBlock" class="block creditsBlock technosBlock">
    <ul class="languages">
        <li>PHP5</li>
        <li>MySQL</li>
        <li>HTML5</li>
        <li>CSS3</li>
        <li>Javascript</li>
    </ul>
</div>

<div id="toolsBlock" class="block creditsBlock thanksBlock">
    <h3>{t}used tools{/t}</h3>
    <ul class="tools">
        <li>PHPGasus (php framework)</li>
        <li>jQuery (javascript lib)</li>
        <li>jQuery UI (javascript lib)</li>
        <li>jQuery Timepicker (javascript lib)</li>
        <li>jQuery Easing (javascript lib)</li>
        <li>Modernizr (javascript lib)</li>
        <li>Smarty (PHP templating lib)</li>
        <li>Minify (PHP JS & CSS minifying lib)</li>
        <li>Fam Fam Fam (icons)</li>
    </ul>
</div>
{/block}