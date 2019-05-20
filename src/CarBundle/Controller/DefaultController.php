<?php

namespace CarBundle\Controller;

use CarBundle\Entity\Car;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use \Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class DefaultController extends Controller
{
    /**
     * @Route("/our-cars", name="offer")
     */
    public function indexAction(Request $request)
    {
        $carRepository = $this->getDoctrine()->getRepository(Car::class);
        $cars = $carRepository->findCarsWithDetails();
        $form = $this->createFormBuilder()
            ->setMethod('GET')
            ->add('search', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 2])
                ]
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            die('form submitted');
        }

        return $this->render('@Car/Default/index.html.twig', array(
            'cars' => $cars,
            'form' => $form->createView()
        ));
    }

    /**
     * @param $id
     * @Route("/car/{id}", name="show_car")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction($id)
    {
        $carRepository = $this->getDoctrine()->getRepository(Car::class);
        $car = $carRepository->findCarWithDetails($id);
        return $this->render('@Car/Default/show.html.twig', array(
            'car' => $car
        ));
    }
}
