<?php

namespace App\Controller;

use App\Entity\Item;
use App\Entity\Menu;
use App\Form\ItemType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @Route("/item")
 */
class ItemController extends AbstractController
{
    private function generateUniqueFileName()
    {
        // md5() reduces the similarity of the file names generated by
        // uniqid(), which is based on timestamps
        return md5(uniqid());
    }

    /**
     * @Route("/add_to_menu/{id}/{canteen}", name="item_menu_add")
     */
    public function AddToMenu(Request $request,$id,$canteen){
        $em = $this->getDoctrine()->getManager();
        $item = $em->getRepository(Item::class)->findOneBy(array('id' => $id));
        if($item == null){
            return $this->render('404.html.twig');
        }
        else{
            $Menu = $em->getRepository(Menu::class)->findOneBy(array('date' => new \DateTime(),'item' => $item,'canteen' => $this->getUser()->getCanteen()));
            if($Menu == null){
                $Menu = new Menu();
                $Menu->setDate(new \DateTime());
                $Menu->setCanteen($canteen);
                $Menu->setItem($item);
                $em->persist($Menu);
                $em->flush();
            }
            return $this->redirectToRoute("items");
        }
    }

    /**
     * @Route("/new", name="item_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $item = new Item();
        $form = $this->createForm(ItemType::class, $item);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $file */
            $file = $form->get('image')->getData();

            $fileName = $this->generateUniqueFileName().'.'.$file->guessExtension();

            // Move the file to the directory where brochures are stored
            try {
                $file->move(
                    $this->getParameter('items_images_directory'),
                    $fileName
                );
            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
            }

            // updates the 'brochure' property to store the PDF file name
            // instead of its contents
            $item->setImage($fileName);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($item);
            $entityManager->flush();

            return $this->redirectToRoute('items');
        }

        return $this->render('item/new.html.twig', [
            'item' => $item,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="item_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Item $item): Response
    {
        $form = $this->createForm(ItemType::class, $item);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('item_index', [
                'id' => $item->getId(),
            ]);
        }

        return $this->render('item/edit.html.twig', [
            'item' => $item,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/delete", name="item_delete")
     */
    public function delete(Request $request, $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $item = $entityManager->getRepository(Item::class)->findOneBy(array('id' => $id));
        if ($item != null) {
            $entityManager->remove($item);
            $entityManager->flush();
        }

        return $this->redirectToRoute('items');
    }
}
