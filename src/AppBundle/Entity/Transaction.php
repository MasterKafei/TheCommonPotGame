<?php

namespace AppBundle\Entity;

/**
 * Transaction
 */
class Transaction
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $taxes = 0;

    /**
     * @var Player
     */
    private $player;

    /**
     * @var Round
     */
    private $round;


    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set taxes.
     *
     * @param int $taxes
     *
     * @return Transaction
     */
    public function setTaxes($taxes)
    {
        $this->taxes = $taxes;

        return $this;
    }

    /**
     * Get taxes.
     *
     * @return int
     */
    public function getTaxes()
    {
        return $this->taxes;
    }

    /**
     * Set player.
     *
     * @param Player $player
     *
     * @return Transaction
     */
    public function setPlayer(Player $player)
    {
        $this->player = $player;

        return $this;
    }

    /**
     * Get player.
     *
     * @return Player
     */
    public function getPlayer()
    {
        return $this->player;
    }

    /**
     * Get round.
     *
     * @return Round
     */
    public function getRound()
    {
        return $this->round;
    }

    /**
     * Set round.
     *
     * @param Round $round
     * @return $this
     */
    public function setRound(Round $round)
    {
        $this->round = $round;

        return $this;
    }
}
