<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Item;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityRepository;
use Exception;
/**
 * ListItemsRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ListItemsRepository extends EntityRepository
{
    public function update($id, Request $request)
    {
        $list = $this->findOneById($id);

        $list->setDescription($request->get('description'));

        foreach ($request->get('items') ?? [] as $item) {
            $itemEntity = new Item();

            $itemEntity->setName($item['name']);
            $itemEntity->setConcluded($item['concluded'] ?? '0');
            $itemEntity->setListItems($list);

            $this->getEntityManager()->persist($itemEntity);
        }
        
        foreach ($request->get('items_existents') ?? [] as $item) {
            $itemEntity = $this->getEntityManager()->getRepository(Item::class)
                ->findOneById($item['id']);
            
            $itemEntity->setConcluded($item['concluded'] ?? '0');

            $this->getEntityManager()->merge($itemEntity);
        }

        try {
            $this->getEntityManager()->flush();
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function destroy($id)
    {
        $em = $this->getEntityManager();

        try {
            $em->remove(
                $em->getReference('AppBundle:ListItems', $id)
            );

            $em->flush();
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}
