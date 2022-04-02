<?php

namespace App\Controller;

use App\Entity\TableBooking;
use App\Form\TableBookingType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TableBookingController extends AbstractController
{
    //page de réservation d'une table
    #[Route('/booking/table', name: 'table_booking')]
    #[IsGranted("ROLE_USER")]
    public function booking(Request $request, EntityManagerInterface $manager ): Response
    {
        $tableBooking = new TableBooking();
        $form = $this->createForm(TableBookingType::class, $tableBooking);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $user = $this->getUser();
            $tableBooking->setBooker($user);

            $manager->persist($tableBooking);
            $manager->flush();

            return $this->redirectToRoute('table_booking_confirmation',['id'=>$tableBooking->getId()]);
        }

        return $this->render('tableBooking/booking.html.twig', [
            "title"=>"Restaurant Shangrila | Réserver une table", 'form'=>$form->createView()
        ]);
    }

    //page de confirmation de la réservation de la table
    #[Route('/booking/table/confirmation/{id}', name:'table_booking_confirmation')]
    public function show(TableBooking $tableBooking):Response{


        return $this->render('tableBooking/show.html.twig', ["title"=>"Restaurant Shangrila | Confirmation de la réservation", 'tableBooking'=>$tableBooking]);
    }
}
