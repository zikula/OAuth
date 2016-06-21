<?php
/**
 * This file is part of the Zikula package.
 *
 * Copyright Zikula Foundation - http://zikula.org/
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Zikula\OAuthModule\Listener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Zikula\Core\Event\GenericEvent;
use Zikula\OAuthModule\Entity\Repository\MappingRepository;
use Zikula\UsersModule\RegistrationEvents;
use Zikula\UsersModule\UserEvents;

class UserDeleteListener implements EventSubscriberInterface
{
    /**
     * @var MappingRepository
     */
    private $mappingRepository;

    /**
     * UserDeleteListener constructor.
     * @param $mappingRepository
     */
    public function __construct(MappingRepository $mappingRepository)
    {
        $this->mappingRepository = $mappingRepository;
    }

    public function deleteUsers(GenericEvent $event)
    {
        $deletedUid = $event->getSubject();
        $this->mappingRepository->removeByZikulaId($deletedUid);
    }

    public static function getSubscribedEvents()
    {
        return [
            UserEvents::DELETE_ACCOUNT => [
                'deleteUsers'
            ],
            RegistrationEvents::DELETE_REGISTRATION => [
                'deleteUsers'
            ]
        ];
    }
}