<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Form\CommentType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CommentController extends Controller
{
    /**
     * @Route("/leaveComment", name="leaveComment")
     */
    public function leaveComment(Request $request, EntityManagerInterface $em, $postId)
    {

        $user_id = $this->getUser()->getId();
        $post_id = $postId;

        $comment = new Comment();
        $commentRepo = $this->getDoctrine()->getRepository(Comment::class);

/*$comment->set*/
                $comment->setDateCreated(new \DateTime());


                $em->persist($comment);
                $em->flush();

                $this->addFlash('success', 'Your comment has been successfully created!');

                return $this->redirectToRoute('home');




    }
}
