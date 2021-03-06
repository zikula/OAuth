<?php

declare(strict_types=1);
/*
 * This file is part of the Zikula package.
 *
 * Copyright Zikula - https://ziku.la/
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Zikula\OAuthModule\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Zikula\Bundle\CoreBundle\Controller\AbstractController;
use Zikula\OAuthModule\Form\Type\SettingsType;
use Zikula\PermissionsModule\Annotation\PermissionCheck;
use Zikula\ThemeModule\Engine\Annotation\Theme;
use Zikula\UsersModule\Collector\AuthenticationMethodCollector;

/**
 * Class ConfigController
 *
 * @PermissionCheck("admin")
 */
class ConfigController extends AbstractController
{
    /**
     * @Route("/settings/{method}")
     * @Template("@ZikulaOAuthModule/Config/settings.html.twig")
     * @Theme("admin")
     */
    public function settings(
        Request $request,
        AuthenticationMethodCollector $authenticationMethodCollector,
        string $method = 'github'
    ): array {
        $form = $this->createForm(SettingsType::class, $this->getVar($method, []));
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid() && $form->get('save')->isClicked()) {
            $this->setVar($method, $form->getData());
            $this->addFlash('success', $this->trans('Settings for %method% saved!', ['%method%' => $method]));
        }

        return [
            'form' => $form->createView(),
            'method' => $authenticationMethodCollector->get($method)
        ];
    }
}
