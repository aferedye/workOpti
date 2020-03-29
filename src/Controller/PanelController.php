<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\SocietyRepository;
use App\Entity\Society;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class PanelController extends AbstractController
{
    /**
     * @Route("/panel", name="panel")
     */
    public function index()
    {
        $form = new Society();
        $formulaire = $this->createFormBuilder($form)
            ->add('societyname', TextType::class)
            ->add('adresse', TextType::class)
            ->add('site', TextType::class)
            ->add('vision', TextType::class)
            ->add('submit', SubmitType::class)
            ->getForm();

            

        return $this->render('panel/index.html.twig', [
            'form' => $formulaire->createView(),
        ]);
    }

    /**
     * @return Response
     * @Route("/panel/addSociety", name="addSociety")
     */
    public function addSociety(Request $request)
    {

        if ($request->getMethod() == 'POST') {

            $societyname = htmlspecialchars($request->request->get('societyname'));
            $adresse = htmlspecialchars($request->request->get('adresse'));
            $site = htmlspecialchars($request->request->get('site'));
            $vision = htmlspecialchars($request->request->get('vision'));

            $form = new Society();
            $form->setSocietyname($societyname);
            $form->setAdresse($adresse);
            $form->setSite($site);
            $form->setVision($vision);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($form);
            $entityManager->flush();
        }

        return $this->redirectToRoute('panel');
    }
}
