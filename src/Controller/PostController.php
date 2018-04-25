<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Form\CommentType;
use App\Form\PostType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityNotFoundException;

class PostController extends Controller
{
    /**
     * @Route("/createPost", name="createPost")
     */
    public function createPost(Request $request, EntityManagerInterface $em)
    {
        $post = new Post();
        $postForm = $this->createForm(PostType::class, $post);
        $postForm->handleRequest($request);

        $user = $this->getUser();

        $post->setUser($user);
        $post->setDateCreated(new \DateTime());

        if ($postForm->isSubmitted() && $postForm->isValid()) {
            try {
                $file = $postForm->get('picture')->getData();
                $fileName = md5(uniqid()) . '.' . $file->guessExtension();

                // moves the file to the directory where brochures are stored
                $file->move(
                    $this->getParameter('postsPics_directory'),
                    $fileName
                );

                $post->setPicture($fileName);

                $em->persist($post);
                $em->flush();

                $this->addFlash('success', 'Your post has been successfully created!');

                return $this->redirectToRoute('home');
            } catch (EntityNotFoundException $e) {
                var_dump($e->getMessage());
            }

        }

        return $this->render('post/createPost.html.twig', [
            'createPostForm' => $postForm->createView()
        ]);
    }
}
