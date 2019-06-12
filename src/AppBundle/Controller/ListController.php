<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ListItems;
use AppBundle\Form\ItemType;
use AppBundle\Form\ListItemsType;
use AppBundle\Repository\ListItemsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
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
     * @var ListItemsRepository
     */
    private $repository;

    public function __construct(ListItemsRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @Route("/", name="index")
     */
    public function indexAction()
    {
        $listItems = $this->repository->findAll();

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

        if ($this->container->get('kernel')->getEnvironment() !== 'test') {
            $listItems->setUser($user);
        }

        $this->repository->persist($listItems);

        return $this->redirectToRoute('lists.index');
    }

    /**
     * @Route("/{id}", name="edit")
     *
     * @param ListItems $list
     * @param Request $request
     *
     * @return Response
     */
    public function editAction(ListItems $list, Request $request)
    {
        $form = $this->createForm(ListItemsType::class, $list);

        $form->add('items', CollectionType::class, [
            'entry_type' => ItemType::class,
            'allow_add' => true,
            'by_reference' => false,
        ]);

        $form->handleRequest($request);

        if (!($form->isSubmitted() && $form->isValid())) {
            return $this->render('lists/edit.html.twig', [
                'form' => $form->createView()
            ]);
        }

        try {
            $this->getDoctrine()->getManager()->flush();

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
            $this->repository->destroy($id);

            return $this->redirectToRoute('lists.index');
        } catch (Exception $e) {
            return $this->redirect($request->headers->get('referer'));
        }
    }
}
