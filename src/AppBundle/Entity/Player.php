<?php

namespace AppBundle\Entity;

/**
 * Player
 */
class Player
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var Game
     */
    private $game;

    /**
     * @var User
     */
    private $user;

    /**
     * @var Vote
     */
    private $vote;

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
     * Get game.
     *
     * @return Game
     */
    public function getGame()
    {
        return $this->game;
    }

    /**
     * Set game.
     *
     * @param Game $game
     * @return Player
     */
    public function setGame($game)
    {
        $this->game = $game;

        return $this;
    }

    /**
     * Get user.
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set user.
     *
     * @param User $user
     * @return Player
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get vote.
     *
     * @return Vote
     */
    public function getVote()
    {
        return $this->vote;
    }

    /**
     * Set vote.
     *
     * @param $vote
     * @return $this
     */
    public function setVote($vote)
    {
        $this->vote = $vote;

        return $this;
    }
}