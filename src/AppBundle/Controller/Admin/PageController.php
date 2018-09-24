<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\News;
use AppBundle\Form\PageType;
use AppBundle\Utils\Slugger;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

/**
 * Controller used to manage page contents in the backend.
 *
 * @Route("/admin/page")
 * @Security("has_role('ROLE_ADMIN')")
 */

class PageController extends Controller
{
    /**
     * Lists all News entities.
     *
     * @Route("/", name="admin_page_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $pages = $em->getRepository(News::class)->findAllPages();

        return $this->render('admin/page/index.html.twig', ['pages' => $pages]);
    }

    /**
     * Creates a new News entity.
     *
     * @Route("/new", name="admin_page_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request, Slugger $slugger)
    {
        $news = new News();
        $news->setAuthor($this->getUser());
        $news->setPostType('page');

        $form = $this->createForm(PageType::class, $news)
            ->add('saveAndCreateNew', SubmitType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($news);
            $em->flush();

            $this->addFlash('success', 'action.created_successfully');

            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('admin_page_new');
            }

            return $this->redirectToRoute('admin_page_index');
        }

        return $this->render('admin/page/new.html.twig', [
            'object' => $news,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing News entity.
     *
     * @Route("/{id}/edit", requirements={"id": "\d+"}, name="admin_page_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, News $news, Slugger $slugger)
    {
        $form = $this->createForm(PageType::class, $news);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'action.updated_successfully');

            return $this->redirectToRoute('admin_page_index');
        }

        return $this->render('admin/page/edit.html.twig', [
            'object' => $news,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Deletes a News entity.
     *
     * @Route("/{id}/delete", methods={"POST"}, name="admin_page_delete")
     */
    public function deleteAction(Request $request, $id, News $page)
    {
        if (!$this->isCsrfTokenValid('delete', $request->request->get('token'))) {
            return $this->redirectToRoute('admin_page_index');
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($page);
        $em->flush();

        $this->addFlash('success', 'action.deleted_successfully');

        return $this->redirectToRoute('admin_page_index');
    }
}
