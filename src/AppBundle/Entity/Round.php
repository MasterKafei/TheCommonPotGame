<?php

namespace AppBundle\Entity;

use AppBundle\Service\Business\RoundBusiness;

/**
 * Round
 */
class Round
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $number;

    /**
     * @var Game
     */
    private $game;

    /**
     * @var Transaction[]
     */
    private $transactions;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set number
     *
     * @param integer $number
     *
     * @return Round
     */
    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * Get number
     *
     * @return int
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Set game
     *
     * @param Game $game
     *
     * @return Round
     */
    public function setGame(Game $game)
    {
        $this->game = $game;

        return $this;
    }

    /**
     * Get game
     *
     * @return Game
     */
    public function getGame()
    {
        return $this->game;
    }

    /**
     * Get transactions.
     *
     * @return Transaction[]
     */
    public function getTransactions()
    {
        return $this->transactions;
    }


    /**
     * Set transactions.
     *
     * @param Transaction[] $transactions
     *
     * @return Round
     */
    public function setTransactions(array $transactions)
    {
        $this->transactions = $transactions;

        return $this;
    }

    /**
     * Add transaction.
     *
     * @param Transaction $transaction
     * @return $this
     */
    public function addTransaction(Transaction $transaction)
    {
        $this->transactions[] = $transaction;

        return $this;
    }
}

