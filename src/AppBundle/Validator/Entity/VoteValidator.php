<?php

namespace AppBundle\Validator\Entity;


use AppBundle\Entity\Vote;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class VoteValidator extends ConstraintValidator
{
    /**
     * @var RegistryInterface
     */
    private $registry;

    public function __construct(RegistryInterface $registry)
    {
        $this->registry = $registry;
    }

    public function validate($value, Constraint $constraint)
    {
        if ($value !== null && $this->isVoteAlreadyMade($value)) {
            $this->context->addViolation($constraint->alreadyVoteMessage);
        }

        if (!$value !== null && $this->isVoteHasCorrectGame($value)) {
            $this->context->addViolation($constraint->gameNotCorrectMessage);
        }
    }

    public function isVoteAlreadyMade(Vote $vote)
    {
        return null !== $this->registry->getRepository(Vote::class)->findOneBy(array(
                'owner' => $vote->getOwner(),
            ));
    }

    public function isVoteHasCorrectGame(Vote $vote)
    {
        return $vote->getOwner()->getGame() === $vote->getTarget()->getGame();
    }
}
