<?php

namespace AppBundle\Validator\Entity;

use Symfony\Component\Validator\Constraint;

/**
 * Class Vote
 * @package AppBundle\Validator\Entity$
 */
class Vote extends Constraint
{
    public $alreadyVoteMessage = 'constraint.vote.already_vote';

    public $gameNotCorrectMessage = 'constraint.vote.game_not_correct';

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}