<?php

declare(strict_types=1);

namespace Jpz\DoubleBanner\Controller\Admin;

use Configuration;
use Jpz\DoubleBanner\Config\BannerKeys;
use Jpz\DoubleBanner\Image\BannerImageStorage;
use PrestaShop\PrestaShop\Core\Form\FormHandlerInterface;
use PrestaShopBundle\Controller\Admin\PrestaShopAdminController;
use PrestaShopBundle\Security\Attribute\AdminSecurity;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

if (!defined('_PS_VERSION_')) {
    exit;
}

/**
 * Pagina di configurazione dei banner (rotta `jpz_doublebanner_configuration`).
 */
class ConfigurationController extends PrestaShopAdminController
{
    public const TAB_CLASS_NAME = 'AdminJpzDoubleBannerConfiguration';

    #[AdminSecurity("is_granted('read', request.get('_legacy_controller'))", message: 'Access denied.')]
    public function index(
        Request $request,
        BannerImageStorage $images,
        #[Autowire(service: 'jpz_doublebanner.form.configuration_handler')]
        FormHandlerInterface $formHandler,
    ): Response {
        $form = $formHandler->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if (!$this->isGranted('update', self::TAB_CLASS_NAME)) {
                $this->addFlash('error', $this->trans('Access denied.', [], 'Admin.Notifications.Error'));

                return $this->redirectToRoute('jpz_doublebanner_configuration');
            }

            if ($form->isValid()) {
                $errors = $formHandler->save($form->getData());

                if (empty($errors)) {
                    $this->addFlash('success', $this->trans('Successful update', [], 'Admin.Notifications.Success'));

                    return $this->redirectToRoute('jpz_doublebanner_configuration');
                }

                $this->addFlashErrors($errors);
            }
        }

        return $this->render('@Modules/jpz_doublebanner/views/templates/admin/configuration.html.twig', [
            'layoutTitle' => $this->trans('Double Banner', [], 'Modules.Jpzdoublebanner.Admin'),
            'configurationForm' => $form->createView(),
            'previews' => $this->getPreviews($images),
        ]);
    }

    /**
     * URL delle immagini attuali, per l'anteprima accanto ai campi di upload.
     *
     * @return array<string, array{desktop: string|null, mobile: string|null}>
     */
    private function getPreviews(BannerImageStorage $images): array
    {
        $previews = [];
        foreach (BannerKeys::banners() as $banner) {
            $previews['banner_' . $banner] = [
                'desktop' => $images->getUrl((string) Configuration::get(BannerKeys::key($banner, BannerKeys::IMAGE_DESKTOP))),
                'mobile' => $images->getUrl((string) Configuration::get(BannerKeys::key($banner, BannerKeys::IMAGE_MOBILE))),
            ];
        }

        return $previews;
    }
}
