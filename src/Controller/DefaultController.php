<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Form\CommentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="home")
     */
    public function home($page = 1, Request $request, EntityManagerInterface $em)
    {
        //get the posts
        $postRepo = $this->getDoctrine()->getRepository(Post::class);
        /*        $movies = $movieRepo->findBy([], ["rating" => "DESC"], 50, 0);*/
        $posts = $postRepo->findAll();

        //get the comments
        $commentRepo = $this->getDoctrine()->getRepository(Comment::class);
        $comments = $commentRepo->findAll();

        //create comment form
        $comment = new Comment();
        $commentForm = $this->createForm(CommentType::class, $comment);
        $commentData = $commentForm->handleRequest($request)->getData();

        //if data is catched we redirect on CommentController
        if ($commentData->getContent()) {
            $post_id = $_POST['postId'];
            $response = $this->forward('App\Controller\CommentController::leaveComment', array(
                'commentData' => $commentData,
                'postId' => $post_id
            ));

          return $response;

        }

        //create home view
        return $this->render('default/index2.html.twig', [
            "posts" => $posts,
            "comments" => $comments,
            "nextPage" => $page + 1,
            "prevPage" => $page - 1,
            "totalResults" => count($posts),
            "lastPage" => ceil(count($posts) / 50),
            'leaveCommentForm' => $commentForm->createView()
        ]);

    }

}





