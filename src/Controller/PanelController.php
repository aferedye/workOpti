<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\SocietyRepository;
use App\Repository\ContactRepository;
use App\Entity\Society;
use App\Entity\Contact;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

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
            ->add('vision', TextareaType::class)
            ->add('submit', SubmitType::class)
            ->getForm();

        $formContact = new Contact();
        $contactform = $this->createFormBuilder($formContact)
            ->add('nom', TextType::class)
            ->add('email', TextType::class)
            ->add('telephone', TextType::class)
            ->add('poste', TextType::class)
            ->add('society', EntityType::class, [
                'class' => Society::class,
                'choice_label' => 'societyname'
            ])
            ->add('submit', SubmitType::class)
            ->getForm();

        $list = $this->getDoctrine()->getRepository(Society::class)->findAll();

        return $this->render('panel/index.html.twig', [
            'form' => $formulaire->createView(),
            'formContact' => $contactform->createView(),
            'list' => $list
        ]);
    }

      /**
     * @return Response
     * @Route("/panel/addContact", name="addContact")
     */
    public function addContact(Request $request)
    {

        if ($request->getMethod() == 'POST') {

            $nom = htmlspecialchars($request->request->get('nom'));
            $email = htmlspecialchars($request->request->get('email'));
            $telephone = htmlspecialchars($request->request->get('telephone'));
            $poste = htmlspecialchars($request->request->get('poste'));
            $society = htmlspecialchars($request->request->get('society'));
            $societyID = $this->getDoctrine()->getRepository(Society::class)->find($society);

            $form = new Contact();
            $form->setNom($nom);
            $form->setEmail($email);
            $form->setTelephone($telephone);
            $form->setPoste($poste);
            $form->setSociety($societyID);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($form);
            $entityManager->flush();
        }

        return $this->redirectToRoute('panel');
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

     /**
     * @Route("/panel/society/details", name="society_details")
     */
    public function societyDetails(Request $request)
    {
        $id = $request->get("iddevis");

        $em = $this->getDoctrine()->getRepository(Society::class);
        $results = $em->findBy(array('id' => $id));
        //$contacts = $results->getContacts();

        dump($results);

        return $this->render('panel/societydetail.html.twig', [
            'results' => $results,
            //'contacts' => $contacts
        ]);
    }

    /**
     * Creates a new ActionItem entity.
     * @return Response
     * @Route("/society/list", name="society_list", methods={"GET","HEAD"})
     */
    public function SocietyList() {

        $lists = $this->getDoctrine()->getRepository(Society::class)->findAll();

        $result = $this->display_json($lists);

        return new Response($result);
    }
     
 
    /**
     * Creates a new ActionItem entity.
     *
     * @Route("/test/search", name="ajax_search", methods={"GET","HEAD"})
     */
    public function searchAction(Request $request)
    {
        $em = $this->getDoctrine()->getRepository(Society::class);

        $requestString = $request->get('q');

        $entities =  $em->findLike($requestString);
            
        if(!$entities) {
            $result['entities']['error'] = "Erreur" ;
        } else {
            $result['entities'] = $this->getRealEntities($entities);
        }

        return new Response(json_encode($result));
    }

    public function getRealEntities($entities){

        foreach ($entities as $entity){
            $realEntities[$entity->getId()] = $entity->getSocietyname();
        }

        return $realEntities;
    }

    public function display_json($lists)
    {
        $list_main = array();
        
            foreach($lists as $list)
            {
                $list_second = array(
                    'id' => $list->getId(),
                    'Name' => $list->getSocietyName(),
                    'Site' => $list->getSite(),
                    'Vision' => $list->getVision(),
                    'contacts' => $list->getContacts()
                );
          
                /*foreach($list->getContacts() as $lis) {
                    $list_third = array(
                        'ContactId' => $lis->getId(),
                        'ContactName' => $lis->getNom(),
                        'ContactEmail' => $lis->getEmail(),
                        'ContactTel' => $lis->getTelephone(),
                        'ContactPoste' => $lis->getPoste(),
                    );
                }*/
            array_push($list_main, $list_second, /*$list_third*/);
            }

            return json_encode($list_main);
    }

    
}
