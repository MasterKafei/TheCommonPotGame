<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Game;
use AppBundle\Entity\Player;
use AppBundle\Entity\Transaction;
use AppBundle\Entity\Vote;
use AppBundle\Form\Type\Game\CreateGameType;
use AppBundle\Form\Type\Transaction\CreateTransactionType;
use AppBundle\Service\Business\GameBusiness;
use AppBundle\Service\Business\RoundBusiness;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class GameController extends Controller
{
    /**
     * @Route(path="/game/create", name="app_game_create", methods={"POST"})
     *
     * @param Request $request
     * @param RoundBusiness $roundBusiness
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function createAction(Request $request, RoundBusiness $roundBusiness)
    {
        $game = new Game();
        $form = $this->createForm(CreateGameType::class, $game);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $round = $roundBusiness->createNewRound($game);
            $game->setOwner($this->getUser());
            $game->addRound($round);

            $em = $this->getDoctrine()->getManager();
            $em->persist($game);
            $em->persist($round);
            $em->flush();
        } else {
            return $this->redirectToRoute('app_game_list');
        }

        return $this->redirectToRoute('app_game_join', array(
            'name' => $game->getName(),
        ));
    }

//    /**
//     * @Route(path="/game/{name}/edit", name="app_game_edit", requirements={"id" = "\d+"})
//     *
//     * @param Request $request
//     * @param Game $game
//     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
//     */
//    public function editAction(Request $request, Game $game)
//    {
//        $form = $this->createForm(EditGameType::class, $game);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $em = $this->getDoctrine()->getManager();
//            $em->persist($game);
//            $em->flush();
//
//            return $this->redirectToRoute('app_game_show', array(
//                'name' => $game->getName(),
//            ));
//        }
//
//        return $this->render('@Page/Game/edit.html.twig', array(
//            'form' => $form->createView(),
//        ));
//    }

    /**
     * @Route(path="/game/{name}/delete", name="app_game_delete")
     *
     * @param Game $game
     * @param GameBusiness $gameBusiness
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Game $game, GameBusiness $gameBusiness)
    {
        if (!$gameBusiness->isUserAllowToDeleteGame($game, $this->getUser())) {
            return $this->redirectToRoute('app_game_show', array(
                'name' => $game->getName(),
            ));
        }

        $em = $this->getDoctrine()->getManager();
        foreach ($game->getPlayers() as $player) {
            $em->remove($player);
        }
        foreach ($game->getRounds() as $round) {
            $em->remove($round);
        }
        $em->flush();
        $em->remove($game);
        $em->flush();

        return $this->redirectToRoute('app_game_list');
    }

    /**
     * @Route(path="/game/{name}", name="app_game_show")
     *
     * @param Game $game
     * @param GameBusiness $gameBusiness
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction(Game $game, GameBusiness $gameBusiness)
    {
        return $this->render('@Page/Game/show.html.twig', array(
            'game' => $game,
            'player' => $gameBusiness->getPlayer($game, $this->getUser()),
            'form' => $this->createForm(CreateTransactionType::class, new Transaction(), ['max' => $game->getRoundMoney(), 'action' => $this->generateUrl('app_game_send_money', ['name' => $game->getName()])])->createView(),
        ));
    }

    /**
     * @Route(path="/games", name="app_game_list")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction()
    {
        $games = $this->getDoctrine()->getManager()->getRepository(Game::class)->findAll();
        $game = new Game();
        $game
            ->setRoundMoney(5)
            ->setRoundNumber(5)
            ->setMaxPlayerNumber(5)
        ;

        return $this->render('@Page/Game/list.html.twig', array(
            'games' => $games,
            'form' => $this->createForm(CreateGameType::class, $game, array(
                'action' => $this->generateUrl('app_game_create')
            ))->createView()
        ));
    }

    /**
     * @Route(path="/game/{name}/join", name="app_game_join")
     *
     * @param Game $game
     * @param GameBusiness $gameBusiness
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function joinAction(Game $game, GameBusiness $gameBusiness)
    {
        if (!$gameBusiness->isUserInGame($game, $this->getUser()) && !$game->getFinished() && count($game->getRounds()) < 2 /*&& ($game->getMaxPlayerNumber() == null || $game->getMaxPlayerNumber() < count($game->getPlayers()))*/) {
            $player = new Player();
            $player
                ->setUser($this->getUser())
                ->setGame($game);

            $game->addPlayer($player);
            $player->setGame($game);

            $em = $this->getDoctrine()->getManager();
            $em->persist($player);
            $em->persist($game);
            $em->flush();
        }

        return $this->redirectToRoute('app_game_show', array(
            'name' => $game->getName(),
        ));
    }

    /**
     * @Route(path="/game/{name}/vote/{id}", name="app_game_vote")
     *
     * @ParamConverter("game", class="AppBundle:Game", options={"mapping": {"name" = "name"}})
     * @ParamConverter("player", class="AppBundle:Player", options={"mapping": {"id" = "id"}})
     *
     * @param Game $game
     * @param Player $target
     * @param GameBusiness $gameBusiness &
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function eliminatePlayer(Game $game, Player $target, GameBusiness $gameBusiness)
    {
        $player = $gameBusiness->getPlayer($game, $this->getUser());

        if ($player->getVote() !== null || $player === $target || $game->getFinished() || !$game->getPlayers()->contains($player) || !$game->getPlayers()->contains($target)) {
            return $this->redirectToRoute('app_game_show', array(
                'name' => $game->getName(),
            ));
        }
        $vote = new Vote();
        $vote
            ->setOwner($player)
            ->setTarget($target);
        $player->setVote($vote);

        $em = $this->getDoctrine()->getManager();
        $em->persist($vote);
        $em->persist($player);
        $em->flush();

        if (null === $game->getEliminatedPlayer()) {
            $eliminatedPlayer = $gameBusiness->getEliminatedPlayer($game);
            if (null !== $eliminatedPlayer) {
                $game->setEliminatedPlayer($eliminatedPlayer);
                $em = $this->getDoctrine()->getManager();
                $em->persist($game);
                $em->flush();
            }
        }

        return $this->redirectToRoute('app_game_show', array(
            'name' => $game->getName(),
        ));
    }

    /**
     * @Route(path="/game/{name}/send-money", methods={"POST"}, name="app_game_send_money")
     *
     * @param Request $request
     * @param Game $game
     * @param GameBusiness $gameBusiness
     * @param RoundBusiness $roundBusiness
     *
     * @return RedirectResponse
     */
    public function sendMoneyAction(Request $request, Game $game, GameBusiness $gameBusiness, RoundBusiness $roundBusiness)
    {
        $transaction = new Transaction();
        $form = $this->createForm(CreateTransactionType::class, $transaction, ['max' => $game->getRoundMoney()]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $round = $roundBusiness->getCurrentRound($game);
            $player = $gameBusiness->getPlayer($game, $this->getUser());

            if ($transaction->getTaxes() > $game->getRoundMoney() || $game->getEliminatedPlayer() === $player || $roundBusiness->doesPlayerAlreadyPaid($round, $player) || $game->getFinished() || !$game->getPlayers()->contains($player)) {
                return $this->redirectToRoute('app_game_show', array(
                    'name' => $game->getName(),
                ));
            }

            $transaction->setRound($round);
            $transaction->setPlayer($player);
            $round->addTransaction($transaction);
            $em = $this->getDoctrine()->getManager();

            if (count($round->getTransactions()) == count($game->getPlayers())) {
                $newRound = $roundBusiness->createNewRound($game);
                if (null !== $newRound) {
                    $em->persist($newRound);
                } else {
                    $game->setFinished(true);
                }
            }

            $em->persist($round);
            $em->persist($transaction);
            $em->persist($player);
            $em->flush();
        }

        return $this->redirectToRoute('app_game_show', array(
            'name' => $game->getName(),
        ));
    }
}
