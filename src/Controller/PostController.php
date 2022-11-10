<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/post", name="post.")
 */
class PostController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"get"})
     */
    public function index(PostRepository $postRepository): Response
    {
        $posts = $postRepository->findAll();
        $this->convertCategories($posts);
        return $this->render('post/index.html.twig', ['posts' => $posts]);
    }

    /**
     * @Route("/create", name="create")
     */
    public function create(Request $request, PostRepository $postRepository)
    {
        $form = $this->createForm(PostType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $data = $form->getData();
            $data->setImage('image-001.jpg');

            $postRepository->add($data, true);
            $this->addFlash('success', 'Create post completed!');
            return $this->redirect($this->generateUrl('post.index'));
        }

        return $this->render('post/create.html.twig', [
            'form'  => $form->createView(),
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit")
     */
    public function edit($id, Request $request, ManagerRegistry $doctrine)
    {
        $entityManager = $doctrine->getManager();
        $post = $entityManager->getRepository(Post::class)->find($id);
        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            // $data = $form->getData();
            // $entityManager = $doctrine->getManager();
            
            $entityManager->flush();
            $this->addFlash('success', 'Update post completed!');
            return $this->redirect($this->generateUrl('post.index'));
        }

        return $this->render('post/update.html.twig', [
            'form'  => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function delete($id, ManagerRegistry $doctrine)
    {
        $entityManager = $doctrine->getManager();
        $post = $entityManager->getRepository(Post::class)->find($id);

        $entityManager->remove($post);
        $entityManager->flush();

        $this->addFlash('success', 'Delete post completed!');
        return $this->redirect($this->generateUrl('post.index'));
    }

    private function makeCategories($data)
    {
        $cateListName = ['category1', 'category2', 'category3', 'category4', 'category5'];
        $cateListValue = [];
        foreach ($cateListName as $cate) {
            if ($data[$cate]) {
                $cateListValue[] = $cate;
            }
        }
        return implode(', ', $cateListValue);
    }

    private function convertCategories(&$posts)
    {
        foreach ($posts as &$post) {
            $result = [];
            $categories = json_decode($post->getCategories());
            $categories = $categories ?? [];
            foreach ($categories as $category) {
                $result[] = $category;
            }
            $post->setCategories(implode(', ', $result));
        }

        return $posts;
    }
}
