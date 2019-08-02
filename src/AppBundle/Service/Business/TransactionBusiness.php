<?php

namespace AppBundle\Service\Business;


use AppBundle\Entity\Player;

class TransactionBusiness
{
    public function getPlayerMoney(Player $player)
    {
        $money = 0;
        $game = $player->getGame();
        foreach ($game->getRounds() as $round) {
            if (count($round->getTransactions()) === count($game->getPlayers())) {
                $totalTaxes = 0;
                foreach ($round->getTransactions() as $transaction) {
                    if ($transaction->getPlayer() === $player) {
                        $money += $transaction->getPiggyBank();
                    }
                    $totalTaxes += $transaction->getTaxes();
                }
                $money += floor($totalTaxes * 2 / count($game->getPlayers()));
            }
        }

        return $money;
    }
}
