<?php

namespace App\Form;

use App\Entity\Booking;
use App\Repository\BookingRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookingCreateType extends AbstractType
{
    /** @var BookingRepository */
    private $bookingRepository;

    public function __construct(BookingRepository $bookingRepository)
    {
        $this->bookingRepository = $bookingRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('beginAt', DateType::class, [
                "widget" => 'single_text'
            ])
            ->add('finishAt', DateType::class, [
                "widget" => 'single_text'
            ])
            ->add('client')
            ->add('room')
            ->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
                /** @var Booking $booking */
                $booking = $event->getData();
                $form = $event->getForm();
                if ($booking->getBeginAt()->format('Y-m-d') < date("Y-m-d")) {
                    throw new BadRequestHttpException('Unable to book in the past');
                }
                if ($booking->getBeginAt()->format('Y-m-d') > $booking->getFinishAt()->format('Y-m-d')) {
                    throw new BadRequestHttpException('The finish date must be greater than the begin date');
                }
                if ($this->bookingRepository->clientAlreadyHaveBooking($booking)) {
                    throw new BadRequestHttpException('The customer already has a reservation in this range');
                }
                if ($this->bookingRepository->isConflictWithAnother($booking)) {
                    throw new BadRequestHttpException('The room is already reserved in this range');
                }
            })
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection'   => false,
            'data_class' => Booking::class,
        ]);
    }
}