<?php

namespace AppBundle\Entity;

/**
 * Game
 */
class Game
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var User
     */
    private $owner;

    /**
     * @var Player[]
     */
    private $players;

    /**
     * @var Player
     */
    private $eliminatedPlayer;

    /**
     * @var Vote[]
     */
    private $votes;

    /**
     * @var int
     */
    private $roundNumber = 5;

    /**
     * @var Round[]
     */
    private $rounds;

    /**
     * @var int
     */
    private $roundMoney = 5;

    /**
     * @var int|null
     */
    private $maxPlayerNumber;

    /**
     * @var bool
     */
    private $finished = false;

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
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set name.
     *
     * @param string $name
     * @return Game
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get owner.
     *
     * @return User
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * Set owner.
     *
     * @param User $owner
     * @return Game
     */
    public function setOwner($owner)
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * Get players.
     *
     * @return Player[]
     */
    public function getPlayers()
    {
        return $this->players;
    }

    /**
     * Set players.
     *
     * @param Player[] $players
     * @return Game
     */
    public function setPlayers($players)
    {
        $this->players = $players;

        return $this;
    }

    /**
     * Add player.
     *
     * @param Player $player
     * @return Game
     */
    public function addPlayer(Player $player)
    {
        if (!$this->players->contains($player)) {
            $this->players[] = $player;
        }

        return $this;
    }

    /**
     * Remove players.
     *
     * @param Player $player
     * @return Game
     */
    public function removePlayer(Player $player)
    {
        $this->players->remove($player);

        return $this;
    }

    /**
     * Set eliminated player.
     *
     * @param Player $eliminatedPlayer
     *
     * @return Game
     */
    public function setEliminatedPlayer($eliminatedPlayer)
    {
        $this->eliminatedPlayer = $eliminatedPlayer;

        return $this;
    }

    /**
     * Get eliminated player.
     *
     * @return Player
     */
    public function getEliminatedPlayer()
    {
        return $this->eliminatedPlayer;
    }

    /**
     * Get votes.
     *
     * @return Vote[]
     */
    public function getVotes()
    {
        return $this->votes;
    }

    /**
     * Set votes.
     *
     * @param Vote[] $votes
     * @return Game
     */
    public function setVotes($votes)
    {
        $this->votes = $votes;

        return $this;
    }

    /**
     * Add vote.
     *
     * @param Vote $vote
     * @return Game
     */
    public function addVote(Vote $vote)
    {
        $this->votes[] = $vote;

        return $this;
    }

    /**
     * Remove vote.
     *
     * @param Vote $vote
     * @return Game
     */
    public function removeVote(Vote $vote)
    {
        $this->votes->remove($vote);

        return $this;
    }

    /**
     * Get round number.
     *
     * @return int
     */
    public function getRoundNumber()
    {
        return $this->roundNumber;
    }

    /**
     * Set round number.
     *
     * @param int $roundNumber
     *
     * @return Game
     */
    public function setRoundNumber(int $roundNumber)
    {
        $this->roundNumber = $roundNumber;

        return $this;
    }

    /**
     * Set round money.
     *
     * @param int $roundMoney
     *
     * @return Game
     */
    public function setRoundMoney(int $roundMoney)
    {
        $this->roundMoney = $roundMoney;

        return $this;
    }

    /**
     * Get round money
     *
     * @return int
     */
    public function getRoundMoney()
    {
        return $this->roundMoney;
    }

    /**
     * Get rounds.
     *
     * @return Round[]
     */
    public function getRounds()
    {
        return $this->rounds;
    }

    /**
     * Set rounds.
     *
     * @param $rounds
     * @return Game
     */
    public function setRounds($rounds)
    {
        $this->rounds = $rounds;

        return $this;
    }

    /**
     * Add round.
     *
     * @param Round $round
     * @return $this
     */
    public function addRound(Round $round)
    {
        $this->rounds[] = $round;

        return $this;
    }

    /**
     * Get max player number.
     *
     * @return int|null
     */
    public function getMaxPlayerNumber()
    {
        return $this->maxPlayerNumber;
    }

    /**
     * Set max player number.
     *
     * @param int|null $maxPlayerNumber
     * @return Game
     */
    public function setMaxPlayerNumber($maxPlayerNumber)
    {
        $this->maxPlayerNumber = $maxPlayerNumber;

        return $this;
    }

    /**
     * Get finished.
     *
     * @return bool
     */
    public function getFinished()
    {
        return $this->finished;
    }

    /**
     * Set finished.
     *
     * @param bool $finished
     *
     * @return Game
     */
    public function setFinished(bool $finished)
    {
        $this->finished = $finished;

        return $this;
    }
}