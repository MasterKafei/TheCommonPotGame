<?php

namespace AppBundle\Controller;


use AppBundle\Entity\User;
use AppBundle\Form\Type\User\LoginUserType;
use AppBundle\Form\Type\User\RegisterUserType;
use AppBundle\Service\Business\UserBusiness;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UserController extends Controller
{
    /**
     * @Route(path="/login", name="app_user_login")
     * @param AuthenticationUtils $utils
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function loginAction(AuthenticationUtils $utils, Request $request)
    {
        $form = $this->createForm(LoginUserType::class);
        $error = $utils->getLastAuthenticationError();
        if ($error !== null) {
            echo $error->getMessage();
        }
        $lastUsername = $utils->getLastUsername();
        $form->get('_username')->setData($lastUsername);
        $form->get('_csrf_token')->setData(
            $this->get('security.csrf.token_manager')->getToken('authenticate')->getValue()
        );
        if (null !== $request->query->get('referer')) {
            $form->get('_target_path')->setData($request->query->get('referer'));
        }
        return $this->render('@Page/User/login.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route(path="/logout", name="app_user_logout")
     */
    public function logoutAction()
    {
        throw new \RuntimeException("Should never be called");
    }

    /**
     * @Route(path="/check", name="app_user_check")
     */
    public function checkAction()
    {
        throw new \RuntimeException("Should never be called");
    }

    /**
     * @Route(path="/register", name="app_user_register")
     *
     * @param Request $request
     * @param UserBusiness $userBusiness
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function registerAction(Request $request, UserBusiness $userBusiness)
    {
        $user = new User();
        $form = $this->createForm(RegisterUserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userBusiness->hashPassword($user);
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('app_user_login');
        }

        return $this->render('@Page/User/register.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}
