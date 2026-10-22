{if !empty($banners)}
    <section class="doublebanner full">
        {* I banner sono in cima alla home: niente lazy load, il primo è l'immagine LCP. *}
        {foreach from=$banners item=banner}
            <div class="doublebanner__banner">
                {if $banner.image}
                    {if $banner@first}{assign var=priority value='high'}{else}{assign var=priority value='auto'}{/if}
                    {include file='module:jpz_doublebanner/views/templates/hook/_picture.tpl'
                        image=$banner.image
                        loading='eager'
                        fetchpriority=$priority
                        alt=$banner.category_name|default:{l s='Banner' d='Modules.Jpzdoublebanner.Front'}}
                {/if}

                {if $banner.category_link}
                    <a href="{$banner.category_link|escape:'htmlall':'UTF-8'}" class="doublebanner__content">
                        {$banner.text nofilter}
                    </a>
                {else}
                    <div class="doublebanner__content">
                        {$banner.text nofilter}
                    </div>
                {/if}
            </div>
        {/foreach}
    </section>
{/if}
