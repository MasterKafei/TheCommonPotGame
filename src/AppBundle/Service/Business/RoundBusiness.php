<?php

namespace AppBundle\Service\Business;

use AppBundle\Entity\Game;
use AppBundle\Entity\Player;
use AppBundle\Entity\Round;

class RoundBusiness
{
    public function getCurrentRound(Game $game)
    {
        $max = 0;
        $currentRound = null;

        foreach ($game->getRounds() as $round) {
            if ($round->getNumber() > $max) {
                $max = $round->getNumber();
                $currentRound = $round;
            }
        }

        return $currentRound;
    }

    public function getPreviousRound(Game $game)
    {
        if (count($game->getRounds()) <= 1) {
            return null;
        }

        $currentNumber = $this->getCurrentRound($game)->getNumber() - 1;

        foreach ($game->getRounds() as $round) {
            if ($round->getNumber() === $currentNumber) {
                return $round;
            }
        }
    }

    public function createNewRound(Game $game)
    {
        if (!$this->canCreateNewRound($game)) {
            return null;
        }

        $round = new Round();
        $number = count($game->getRounds());

        return $round
            ->setNumber($number + 1)
            ->setGame($game);

    }

    public function canCreateNewRound(Game $game)
    {
        return count($game->getRounds()) < $game->getRoundNumber();
    }

    public function doesPlayerAlreadyPaid(Round $round, Player $player)
    {
        foreach ($round->getTransactions() as $transaction) {
            if ($transaction->getPlayer() === $player) {
                return true;
            }
        }

        return false;
    }

    public function getRoundTaxes(Round $round)
    {
        $taxes = 0;

        foreach ($round->getTransactions() as $transaction) {
            $taxes += $transaction->getTaxes();
        }

        return $taxes;
    }
}
