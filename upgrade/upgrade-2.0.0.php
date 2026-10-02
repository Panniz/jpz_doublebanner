<?php

declare(strict_types=1);

if (!defined('_PS_VERSION_')) {
    exit;
}

use Jpz\DoubleBanner\Install\Installer;

/**
 * 2.0.0: configurazione in una pagina Symfony e immagine mobile per banner.
 *
 * L'immagine esistente resta sulla chiave `B{N}_IMAGE` e diventa quella
 * desktop, quindi non c'è nulla da spostare: si aggiungono le chiavi mancanti
 * (`B{N}_IMAGE_MOBILE`) e il Tab che regola i permessi della nuova pagina.
 * hookActionAdminControllerSetMedia non serve più: lo si stacca.
 */
function upgrade_module_2_0_0(Jpz_DoubleBanner $module): bool
{
    $installer = new Installer();

    $module->unregisterHook('actionAdminControllerSetMedia');

    return $installer->installConfiguration()
        && $installer->installUploadDirectory()
        && $installer->installTab($module);
}
