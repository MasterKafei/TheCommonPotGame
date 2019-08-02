<?php

namespace AppBundle\Service\Business;

use AppBundle\Entity\User;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

/**
 * Class UserBusiness
 * @package AppBundle\Service\Business
 */
class UserBusiness
{
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @var \Symfony\Component\HttpFoundation\Session\SessionInterface
     */
    private $session;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var EncoderFactoryInterface
     */
    private $encoderFactory;

    /**
     * Constructor.
     *
     * @param TokenStorageInterface $storage
     * @param Request $request
     * @param EncoderFactoryInterface $encoderFactory
     */
    public function __construct(TokenStorageInterface $storage, RequestStack $request, EncoderFactoryInterface $encoderFactory)
    {
        $this->tokenStorage = $storage;
        $this->session = $request->getMasterRequest()->getSession();
        $this->encoderFactory = $encoderFactory;
    }

    /**
     * Authenticate current user as the defined User
     *
     * @param User $user : User to authenticate as
     */
    public function authenticateUser(User $user)
    {
        $token = new UsernamePasswordToken($user, null, 'secured_area', $user->getRoles());
        $this->tokenStorage->setToken($token);
        $this->session->set('_security_secured_area', serialize($token));
    }

    /**
     * Hash a password with hash strategy defined in security
     *
     * @param User $user
     *
     * @return string
     * @throws \Exception
     */
    public function hashPassword(User $user)
    {
        if (null === $user->getPlainPassword()) {
            throw new \Exception('Plain password can\'t be null');
        }
        $encoder = $this->encoderFactory->getEncoder($user);
        $password = $encoder->encodePassword($user->getPlainPassword(), $user->getSalt());
        $user->setPassword($password);
        return $password;
    }

    /**
     * Get current authenticated user
     *
     * @return User
     */
    public function getCurrentUser()
    {
        $user = $this->tokenStorage->getToken()->getUser();
        if ($user instanceof User) {
            return $user;
        }
        return null;
    }

    // -------------------------------------------------- //
    // ---------------------- Tests --------------------- //
    // -------------------------------------------------- //

    /**
     * Check if the password is valid
     *
     * @param User $user : Owner
     * @param string $password : Password to check
     *
     * @return bool
     */
    public function isPasswordValid(User $user, $password)
    {
        $encoder = $this->encoderFactory->getEncoder($user);
        return $encoder->isPasswordValid($user->getPassword(), $password, $user->getSalt());
    }

    /**
     * Is user password defined.
     *
     * @param User $user
     *
     * @return bool
     */
    public function isUserPasswordDefined(User $user)
    {
        return (null !== $user->getPassword());
    }
}