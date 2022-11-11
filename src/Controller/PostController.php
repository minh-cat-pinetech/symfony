<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use App\Services\FileUploader;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * @Route("/post", name="post.")
 */
class PostController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"get"})
     */
    public function index(Request $request, PostRepository $postRepository, PaginatorInterface $paginator): Response
    {
        $posts = $postRepository->findAll();
        $this->convertCategories($posts);

        $posts = $paginator->paginate($posts, $request->query->getInt('page', 1), 5);

        return $this->render('post/index.html.twig', ['posts' => $posts]);
    }

    /**
     * @Route("/create", name="create")
     */
    public function create(Request $request, PostRepository $postRepository, FileUploader $fileUploader)
    {
        $form = $this->createForm(PostType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $data = $form->getData();

            // handle image
            $image = $form->get('image')->getData();
            if ($image) {
                $imageName = $fileUploader->upload($image);
                $data->setImage($imageName);
            }

            $postRepository->add($data, true);
            $this->addFlash('success', 'Create post completed!');
            return $this->redirect($this->generateUrl('post.index'));
        }
        // dump($form->createView());
        // dump($form->createView()->vars);
        // die;

        return $this->render('post/create.html.twig', [
            'form'  => $form->createView(),
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit")
     */
    public function edit($id, Request $request, ManagerRegistry $doctrine, FileUploader $fileUploader)
    {
        $entityManager = $doctrine->getManager();
        $post = $entityManager->getRepository(Post::class)->find($id);
        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $data = $form->getData();
            // handle image
            $image = $form->get('image')->getData();
            if ($image) {
                $imageName = $fileUploader->upload($image);
                $data->setImage($imageName);
            }
            $entityManager = $doctrine->getManager();
            
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

    /**
     * @Route("/search", name="search")
     */
    public function search(Request $request, PostRepository $postRepository)
    {
        $searchKey = $request->query->all();

        $query = $postRepository->createQueryBuilder('post')
                                ->where('post.title LIKE :title')
                                ->setParameter('title', '%'.$searchKey['title'].'%')
                                ->getQuery();
        $posts = $query->getResult();
        $posts = array_slice($posts, 0, 5);
        $this->convertCategories($posts);

        // return $this->json(['posts' => $posts]);
        return $this->render('post/table.html.twig', [
            'posts'  => $posts,
        ]);
    }

    /**
     * @Route("/pagination", name="pagination")
     */
    public function paginationAPI(Request $request, PostRepository $postRepository, PaginatorInterface $paginator)
    {
        $searchKey = $request->query->all();

        $query = $postRepository->createQueryBuilder('post')
                                ->where('post.title LIKE :title')
                                ->setParameter('title', '%'.$searchKey['title'].'%')
                                ->getQuery();
        $posts = $query->getResult();
        $this->convertCategories($posts);
        $posts = $paginator->paginate($posts, $request->query->getInt('page', 1), 5);

        // return $this->json(['posts' => $posts]);
        return $this->render('post/pagination.html.twig', [
            'posts'  => $posts,
        ]);
    }

    /**
     * @param Post[] $posts
     */
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
