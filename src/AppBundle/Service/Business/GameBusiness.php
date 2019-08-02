<?php

namespace AppBundle\Service\Business;


use AppBundle\Entity\Game;
use AppBundle\Entity\Player;
use AppBundle\Entity\User;
use Symfony\Bridge\Doctrine\RegistryInterface;

class GameBusiness
{
    /**
     * @var RegistryInterface
     */
    private $registry;

    public function __construct(RegistryInterface $registry)
    {
        $this->registry = $registry;
    }

    public function isUserInGame(Game $game, User $user = null)
    {
        if(null === $user) {
            return false;
        }

        return null !== $this->getPlayer($game, $user);
    }

    public function getPlayer(Game $game, User $user)
    {
        return $this->registry->getRepository(Player::class)->findOneBy(
            array(
                'game' => $game,
                'user' => $user,
            )
        );
    }

    public function getEliminatedPlayer(Game $game)
    {
        $players = $game->getPlayers();
        $votedPlayers = [];
        foreach ($players as $player) {
            if (null === $player->getVote()) {
                return null;
            }
            $votedPlayerId = $player->getVote()->getTarget()->getId();

            $votedPlayers[$votedPlayerId] = ($votedPlayers[$votedPlayerId] ?? 0) + 1;
        }

        $max = 0;
        $maxIdPlayers = [];
        foreach ($votedPlayers as $id => $voteNumber) {
            if($voteNumber > $max) {
                $max = $voteNumber;
                $maxIdPlayers = [$id];
            } else if($voteNumber == $max) {
                $maxIdPlayers[] = $id;
            }
        }

        return $this->registry->getRepository(Player::class)->find(rand(0, count($maxIdPlayers) - 1));
    }

    public function isUserAllowToDeleteGame(Game $game, User $user = null)
    {
        return $game->getOwner() === $user;
    }
}
