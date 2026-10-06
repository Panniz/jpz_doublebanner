{if !empty($banners)}
    <section class="doublebanner full">
        {foreach from=$banners item=banner}
            <div class="doublebanner__banner">
                {if $banner.image}
                    {include file='module:jpz_doublebanner/views/templates/hook/_picture.tpl'
                        image=$banner.image
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
