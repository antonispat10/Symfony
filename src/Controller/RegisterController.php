<?php
/**
 * Created by PhpStorm.
 * User: Antonis
 * Date: 6/25/2018
 * Time: 11:09 PM
 */

namespace App\Controller;


use App\Entity\User;
use App\Event\UserRegisterEvent;
use App\Form\UserType;
use App\Security\TokenGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegisterController extends Controller
{
    /**
     * @Route("/register",name="user_register")
     */
    public function register(UserPasswordEncoderInterface $passwordEncoder,TokenGenerator $tokenGenerator,
                             Request $request, EventDispatcherInterface $eventDispatcher)
    {

        $user = new User();

        $form = $this->createForm(
            UserType::class,
        $user
        );

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $password = $passwordEncoder->encodePassword(
                $user,
                $user->getPlainPassword());

            $user->setPassword($password);
            $user->setConfirmationToken($tokenGenerator->getRandomSecureToken(30));

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $userRegisterEvent = new UserRegisterEvent($user);
            $eventDispatcher->dispatch(
                UserRegisterEvent::NAME,
                $userRegisterEvent
            );
            $this->redirect('micro_post_index');


            $session = new Session();
            $session->getFlashBag()->add('register', 'New User Created');
            return $this->redirect($request->getUri());

        }



        return $this->render('register/register.html.twig',[
            'form' =>$form->createView()
        ]);
    }
}