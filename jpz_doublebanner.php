<?php

declare(strict_types=1);

if (!defined('_PS_VERSION_')) {
    exit;
}

if (!file_exists(__DIR__ . '/vendor/autoload.php')) {
    return;
}

require_once __DIR__ . '/vendor/autoload.php';

use Jpz\DoubleBanner\Image\BannerImageStorage;
use Jpz\DoubleBanner\Install\Installer;
use Jpz\DoubleBanner\Presenter\BannerPresenter;
use PrestaShop\PrestaShop\Adapter\SymfonyContainer;

/**
 * Due banner in homepage, ciascuno con immagine desktop e mobile, testo e link a categoria.
 *
 * La configurazione è una pagina Symfony (Jpz\DoubleBanner\Controller\Admin\
 * ConfigurationController, rotta `jpz_doublebanner_configuration`, permessi sul
 * Tab AdminJpzDoubleBannerConfiguration): getContent() si limita a rediriggere lì.
 */
class Jpz_DoubleBanner extends Module
{
    public function __construct()
    {
        $this->name = 'jpz_doublebanner';
        $this->tab = 'front_office_features';
        $this->version = '2.0.0';
        $this->author = 'Jacopo Zane';
        $this->need_instance = 0;
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->trans('Double Banner', [], 'Modules.Jpzdoublebanner.Admin');
        $this->description = $this->trans('Adds two customizable banners to homepage with image, text and category link.', [], 'Modules.Jpzdoublebanner.Admin');

        $this->ps_versions_compliancy = ['min' => '9.0.0', 'max' => _PS_VERSION_];
    }

    public function isUsingNewTranslationSystem(): bool
    {
        return true;
    }

    public function install(): bool
    {
        return parent::install()
            && $this->registerHook('displayHome')
            && (new Installer())->install($this);
    }

    public function uninstall(): bool
    {
        return (new Installer())->uninstall() && parent::uninstall();
    }

    public function getContent(): string
    {
        $router = SymfonyContainer::getInstance()?->get('router');
        if ($router !== null) {
            Tools::redirectAdmin($router->generate('jpz_doublebanner_configuration'));
        }

        return '';
    }

    public function hookDisplayHome(array $params): string
    {
        $this->context->controller->registerStylesheet('jpz-doublebanner', 'modules/' . $this->name . '/views/css/front.css');

        $presenter = new BannerPresenter(new BannerImageStorage(), $this->context->link);
        $banners = $presenter->present((int) $this->context->language->id);

        if (empty($banners)) {
            return '';
        }

        $this->context->smarty->assign('banners', $banners);

        return $this->fetch('module:jpz_doublebanner/views/templates/hook/displayHome.tpl');
    }
}
