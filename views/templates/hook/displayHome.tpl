{if isset($banners) && !empty($banners)}
    {assign var="bannerCount" value=count($banners)}
    <div id="jpzdoublebanner__container" class="row">
        {foreach from=$banners item=banner}
            {if ($banner.image_url || $banner.text)}
                <div class="jpzdoublebanner__banner col-xs-12 {if $bannerCount>1}col-md-6{else}col-md-12{/if}">
                    {if $banner.category_link}
                        <a href="{$banner.category_link|escape:'htmlall':'UTF-8'}">
                        {/if}

                        {if $banner.image_url}
                            <img src="{$banner.image_url|escape:'htmlall':'UTF-8'}"
                                alt="{l s='Banner' d='Modules.Jpzdoublebanner.Front'}" class="img-responsive" />
                        {/if}

                        {if $banner.category_link}
                        </a>
                    {/if}

                    {if $banner.text}
                        <div class="banner-text">
                            {$banner.text nofilter}
                        </div>
                    {/if}

                    {if $banner.category_link && $banner.category_name}
                        <div class="jpzdoublebanner__category-link">
                            <a href="{$banner.category_link|escape:'htmlall':'UTF-8'}">
                                {l s='Discover More on' d='Modules.Jpzdoublebanner.Front'} {$banner.category_name|escape:'htmlall':'UTF-8'}
                            </a>
                        </div>
                    {/if}
                </div>
            {/if}
        {/foreach}
    </div>
{/if}