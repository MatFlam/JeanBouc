<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Form\CommentType;
use Doctrine\DBAL\Driver\PDOException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CommentController extends Controller
{
    /**
     * @Route("/leaveComment", name="leaveComment")
     */
    public function leaveComment(Request $request, EntityManagerInterface $em)
    {

        $user = $this->getUser();
        $post_id = $_POST['postId'];
        $title = $_POST['title'];
        $content = $_POST['content'];

        if (!empty ($user) && !empty($post_id)) {


            $postRepo = $this->getDoctrine()->getRepository(Post::class);
            $post = $postRepo->find($post_id);

            $comment = new Comment();
            $this->getDoctrine()->getRepository(Comment::class);

            /*            $comment->setTitle($commentData->getTitle());
                        $comment->setContent($commentData->getContent());*/
            $comment->setTitle($title);
            $comment->setContent($content);
            $comment->setUser($user);
            $comment->setPost($post);
            $comment->setDateCreated(new \DateTime());

            try {
                $em->persist($comment);
                $em->flush();
                $this->addFlash('success', 'Your comment has been successfully created!');

                return $this->redirectToRoute('home');
            } catch (PDOException $e) {

            }
        }


    }
}
