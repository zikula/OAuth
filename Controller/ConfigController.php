<?php

namespace Zikula\OAuthModule\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Zikula\Core\Controller\AbstractController;
use Zikula\ThemeModule\Engine\Annotation\Theme;

/**
 * Class ConfigController
 */
class ConfigController extends AbstractController
{
    /**
     * @Route("/settings/{method}")
     * @Theme("admin")
     * @Template
     * @param Request $request
     * @param string $method
     * @return array
     */
    public function settingsAction(Request $request, $method = 'github')
    {
        $form = $this->createForm('Zikula\OAuthModule\Form\Type\SettingsType', $this->getVar($method, []), [
            'translator' => $this->getTranslator()
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('save')->isClicked()) {
                $this->setVar($method, $form->getData());
                $this->addFlash('success', $this->__f('Settings for %method saved!', ['%method' => $method]));
            }
        }

        return [
            'form' => $form->createView(),
            'method' => $this->get('zikula_users_module.internal.authentication_method_collector')->get($method)
        ];
    }
}
