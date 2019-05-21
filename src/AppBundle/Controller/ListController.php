<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ListItems;
use AppBundle\Form\ListItemsType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
    public function indexAction()
    {
        $listItems = $this->getDoctrine()
                ->getRepository(ListItems::class)
                ->findAll();

        return $this->render('lists/index.html.twig', ['lists' => $listItems]);
    }

    /**
     * @Route("/create", name="create")
     *
     * @param Request $request
     * @param UserInterface $user
     *
     * @return Response
     */
    public function createAction(Request $request, UserInterface $user)
    {
        $listItems = new ListItems();

        $form = $this->createForm(ListItemsType::class, $listItems);
        $form->handleRequest($request);

        if (!($form->isSubmitted() && $form->isValid())) {
            return $this->render('lists/create.html.twig', [
                'form' => $form->createView()
            ]);
        }

        $listItems->setUser($user);

        $this->getDoctrine()
            ->getRepository(ListItems::class)
            ->save($listItems);

        return $this->redirectToRoute('lists.index');
    }

    /**
     * @Route("/{id}", name="edit")
     *
     * @param int $id
     * @param Request $request
     *
     * @return Response
     */
    public function editAction(int $id, Request $request)
    {
        $list = $this->getDoctrine()
            ->getRepository(ListItems::class)
            ->findOneById($id);

        $form = $this->createForm(ListItemsType::class, $list);

        $form->handleRequest($request);

        if (!($form->isSubmitted() && $form->isValid())) {
            return $this->render('lists/edit.html.twig', [
                'list' => $list,
                'i' => 0,
                'form' => $form->createView()
            ]);
        }

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
     *
     * @param int id
     * @param Request $request
     *
     * @return Response
     */
    public function destroyAction(int $id, Request $request)
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
