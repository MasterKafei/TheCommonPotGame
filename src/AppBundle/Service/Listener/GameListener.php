<?php

namespace AppBundle\Service\Listener;

use AppBundle\Entity\Game;
use Doctrine\ORM\Event\LifecycleEventArgs;

class GameListener
{
    public function prePersist(Game $game, LifecycleEventArgs $args)
    {
        $this->replaceCharacters($game);
    }

    public function preUpdate(Game $game, LifecycleEventArgs $args)
    {
        $this->replaceCharacters($game);
    }

    private function replaceCharacters(Game $game)
    {
        $game->setName(str_replace(' ', '-', $game->getName()));
    }
}
