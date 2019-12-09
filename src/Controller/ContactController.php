<?php

namespace App\Controller;

use App\Form\ContactType;
use App\Service\Currency;
use Swift_Mailer;
use Swift_Message;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\VarDumper\VarDumper;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     */
    public function contact(Request $request, Swift_Mailer $mailer, Currency $currency)
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();

            $message = (new Swift_Message('New Mail from Symfony Web Site'))
                ->setFrom($formData['email'])
                ->setTo('54521445784254@mailinator.com')
                ->setBody($formData['message'], 'text\plain');

            if ($mailer->send($message) == 0)  $this->addFlash('error', 'Your message couldn\'t reach us.');
            else $this->addFlash('success', 'We received your message.');

            return $this->redirectToRoute('contact');
        }

        return $this->render('contact/index.html.twig', [
            'our_form' => $form->createView(),
            'currency_usd' => $currency->getUSD()
        ]);
    }
}
