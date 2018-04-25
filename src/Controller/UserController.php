<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


class UserController extends Controller
{
    /**
     * @Route("/registerUser", name="registerUser")
     */
    public function registerUser(Request $request, EntityManagerInterface $em, UserPasswordEncoderInterface $encoder, \Swift_Mailer $mailer)
    {
        $user = new User();
        $registerForm = $this->createForm(RegisterType::class, $user);
        $registerForm->handleRequest($request);
        $user->setDateCreated(new \DateTime());

        if ($registerForm->isSubmitted() && $registerForm->isValid()) {
            //hashe le password
            $encoded = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($encoded);
            $user->setRoles(["ROLE_USER"]);

            $file = $registerForm->get('image')->getData();
            var_dump($file);


            $fileName = md5(uniqid()).'.'.$file->guessExtension();

            // moves the file to the directory where brochures are stored
            $file->move(
                $this->getParameter('profilesPics_directory'),
                $fileName
            );

            // updates the 'brochure' property to store the PDF file name
            // instead of its contents
            $user->setImage($fileName);


            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Your account has been successfully created, you must have received a confirmation mail');
        }

            return $this->render("user/registerUser.html.twig", [
                "registerUserForm" => $registerForm->createView()
            ]);

        }

    /**
     * @Route("/login", name="login")
     */
    public function login(Request $request, AuthenticationUtils $authenticationUtils)
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        var_dump($lastUsername);

        //on bloque l'accès si deja connecté
        if ($this->getUser()){
            return $this->redirectToRoute("home");
        }

        return $this->render('security/login.html.twig', array(
            'last_username' => $lastUsername,
            'error'         => $error,
        ));
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout() {

    }


}
