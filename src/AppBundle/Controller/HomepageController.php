<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\Entity\NewsCategory;
use AppBundle\Entity\News;

class HomepageController extends Controller
{
    public function indexAction(Request $request)
    {
        $listCategoriesOnHomepage = $this->get('settings_manager')->get('listCategoryOnHomepage');
        $blocksOnHomepage = array();

        if (!empty($listCategoriesOnHomepage)) {
            $listCategoriesOnHomepage = explode(',', $listCategoriesOnHomepage);

            if (is_array($listCategoriesOnHomepage)) {
                for ($i = 0; $i < count($listCategoriesOnHomepage); $i++) {
                    $blockOnHomepage = [];
                    $category = $this->getDoctrine()
                                    ->getRepository(NewsCategory::class)
                                    ->find($listCategoriesOnHomepage[$i]);

                    if ($category) {
                        $posts = $this->getDoctrine()
                            ->getRepository(News::class)
                            ->findBy(
                                array('postType' => 'post', 'enable' => 1, 'category' => $category->getId()),
                                array('createdAt' => 'DESC'),
                                12
                            );
                    }

                    $blockOnHomepage = (object) array('category' => $category, 'posts' => $posts);
                    $blocksOnHomepage[] = $blockOnHomepage;
                }
            }
        }

        return $this->render('homepage/index.html.twig', [
            'blocksOnHomepage' => $blocksOnHomepage,
        ]);
    }
}
