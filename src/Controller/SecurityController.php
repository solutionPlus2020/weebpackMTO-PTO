<?php

namespace App\Controller;
use App\Entity\ChangePwd;

use App\Entity\ForgotPwd;
use App\Entity\User;
use App\Form\ChangePwdType;
use App\Form\ForgotFormType;;
use App\Form\SignUpType;
use App\Security\LoginFormAuthenticator;
use App\Services\MyMailer;
use App\Services\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


class SecurityController extends AbstractController
{
    /**
     * @Route("/", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * @IsGranted("ROLE_ADMIN", message="vous n'avez pas l'accés !")
     * @Route("/signup", name="app_signup")
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function register(
        EntityManagerInterface $manager ,Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, LoginFormAuthenticator $authenticator
    )
    {
        // whatever *your* User object is
        $user = new User();
        $form = $this->createForm(SignUpType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setEnable(1);
            $user->setToken(uniqid());
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $manager->persist($user);
            $manager->flush();
            $this->addFlash(
                'success',
                'votre inscription a été effectué avec succés.'
            );

            //return $this->redirectToRoute('app_login');
            return $guardHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $authenticator,
                'main' // firewall name in security.yaml
            );
        }

        return $this->render('security/signup.html.twig', [
            'form' => $form->createView()
        ]);
    }



    /**
     * @Route ("confirm/{token}", name="app_confirm")
     */
    public function confirm($token, UserService $userService)
    {
        $user = $this->fetchUserByToken($token);

        if ($user) {
            $userService->confirmUser($user);
            $this->addFlash(
                'success',
                'Votre compte est validé, veuillez vous coneceter.'
            );

            return $this->redirectToRoute('app_login');
        }
    }

    /**
     * @Route ("forgotPwd", name="forgot_pwd")
     */
    public function forgotPwd(
        Request $request,
        UserPasswordEncoderInterface $encoder,
        MyMailer $mailer,
        UserService $userService)
    {
        $forgotPwd = new ForgotPwd();
        $form = $this->createForm(ForgotFormType::class, $forgotPwd);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $user = $this->getDoctrine()->getRepository(User::class)
                ->findOneBy(['email' => $forgotPwd->getEmail()]);

            if ($user) {
                $userService->processForgotPwd($user);
                $this->addFlash(
                    'info',
                    'voici un code permanant, un email vous a été enevoyé.'
                );

                return $this->redirectToRoute('app_login');
            }

        }
        return $this->render('security/forgot.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route ("changePwd", name="change_pwd")
     */
    public function changePwd(
        Request $request,
        UserPasswordEncoderInterface $passwordEncoder,
        MyMailer $mailer,
        UserService $userService
    )
    {
        $changePwd = new changePwd();
        $form = $this->createForm(ChangePwdType::class,
            $changePwd);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $user = $this->getUser();

            if ($passwordEncoder
                ->isPasswordValid(
                    $user,
                    $changePwd->getOldPwd()
                )
            ) {
                $user->setPassword($passwordEncoder
                    ->encodePassword($user,
                        $changePwd->getNewPwd()));
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();
                return $this->redirectToRoute('offer_index');

            }
        }
        return $this->render(
            'security/change_pwd.html.twig',
            [
                'form' => $form->createView()
            ]
        );

    }

    private function fetchUserByToken($token)
    {
        return $this
            ->getDoctrine()
            ->getRepository(User::class)
            ->findOneBy(['token' => $token]);
    }
}
