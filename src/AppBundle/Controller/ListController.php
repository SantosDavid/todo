<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ListItems;
use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Exception;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @Route("/lists", name="lists.")
 */
class ListController extends Controller
{
    /**
     * @Route("/", name="index")
     */
    public function indexAction(UserInterface $user)
    {
        $listItems = $user->getListItems();

        return $this->render('lists/index.html.twig', ['lists' => $listItems]);
    }

    /**
     * @Route("/create", name="create")
     */
    public function createAction()
    {
        return $this->render('lists/create.html.twig');
    }

    /**
     * @Route("store", name="store", methods="POST")
     */
    public function storeAction(Request $request, UserInterface $user)
    {
        $listItems = new ListItems();

        $listItems->setName($request->get('name'));
        $listItems->setUser($user);
        $listItems->setDescription($request->get('description'));

        $errors = $this->get('validator')->validate($listItems);

        if (count($errors) > 0) {
            return $this->render('lists/create.html.twig', ['errors' => $errors]);
        }

        $entityManager = $this->getDoctrine()->getManager();

        $entityManager->persist($listItems);

        $entityManager->flush();

        return $this->redirectToRoute('lists.index');
    }

    /**
     * @Route("/{id}", name="edit", methods="GET")
     */
    public function editAction($id)
    {
        $list = $this->getDoctrine()
            ->getRepository(ListItems::class)
            ->findOneById($id);

        return $this->render('lists/edit.html.twig', ['list' => $list, 'i' => 0]);
    }

    /**
     * @Route("/{id}/update", name="update", methods="PUT")
     */
    public function updateAction($id, Request $request)
    {
        try {
            $this->getDoctrine()
                ->getRepository(ListItems::class)
                ->update($id, $request);

            return $this->redirectToRoute('lists.index');
        } catch (Exception $e) {
            return $this->redirect($request->headers->get('referer'));
        }
    }

    /**
     * @Route("{id}", name="destroy", methods="DELETE")
     */
    public function destroyAction($id, Request $request)
    {
        try {
            $this->getDoctrine()
                ->getRepository(ListItems::class)
                ->destroy($id);

            return $this->redirectToRoute('lists.index');
        } catch (Exception $e) {
            return $this->redirect($request->headers->get('referer'));
        }
    }
}
