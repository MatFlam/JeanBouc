<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Form\CommentType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="home")
     */
    public function home($page = 1)
    {
        $postRepo = $this->getDoctrine()->getRepository(Post::class);
        /*        $movies = $movieRepo->findBy([], ["rating" => "DESC"], 50, 0);*/
        $posts = $postRepo->findAll();

        $comment = new Comment();
        $commentForm = $this->createForm(CommentType::class, $comment);

        return $this->render('default/index.html.twig', [
            "posts"=>$posts,
            "nextPage"=>$page+1,
            "prevPage"=>$page-1,
            "totalResults" => count($posts),
            "lastPage"=> ceil(count($posts)/50),
            'leaveCommentForm' => $commentForm->createView()
        ]);


    }
}


