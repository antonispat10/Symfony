<?php
/**
 * Created by PhpStorm.
 * User: Antonis
 * Date: 7/1/2018
 * Time: 5:44 PM
 */

namespace App\Controller;

use App\Repository\NotificationRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Security("is_granted('ROLE_USER')")
 * @Route("/notification")
 */
class NotificationController extends Controller
{
    public function __construct(NotificationRepository $notificationRepository)
    {
        $this->notificationRepository = $notificationRepository;
    }

    /**
     * @Route("/unread-count", name="notification_unread")
     */
    public function unreadCount()
    {
        new JsonResponse([
            'count' => $this->notificationRepository->findUnseenByUser($this->getUser())
        ]);
    }
}