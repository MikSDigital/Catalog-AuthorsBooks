<?php

namespace AppBundle\Controller;


//use Doctrine\DBAL\Types\TextType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\Entity\Author;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class AuthorController extends Controller{
    /**
     * @Route("/author/index", name="author_list")
     */
    public function indexAction(Request $request){
        $authors = $this->getDoctrine()->getRepository('AppBundle:Author')->findAll();
        return $this->render('author/index.html.twig', array('authors' => $authors));
    }

    /**
     * @Route("/author/create", name="author_create")
     */
    public function createAction(Request $request){
        $author = new Author();
        $form = $this->createFormBuilder($author)
            ->add('surname', TextType::class, array(
                'label' => 'Фамилия',
                'attr' => array(
                    'class' => 'form-control',
                'style' => 'margin-bottom:15px')))
            ->add('name', TextType::class, array(
                'label' => 'Имя',
                'attr' => array(
                    'class' => 'form-control',
                'style' => 'margin-bottom:15px')))
            ->add('patronymic', TextType::class, array(
                'label' => 'Отчество',
                'required' => false,
                'attr' => array(
                    'class' => 'form-control',
                'style' => 'margin-bottom:15px')))
            ->add('Save', SubmitType::class, array(
                'label' => 'Добавить автора',
                'attr' => array(
                    'class' => 'btn btn-success',
                'style' => 'margin-bottom:15px')))
            ->getForm();
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            //get data
            $surname = $form['surname']->getData();
            $name = $form['name']->getData();
            $patronymic = $form['patronymic']->getData();

            $author->setSurname($surname);
            $author->setName($name);
            $author->setPatronymic($patronymic);

            $em = $this->getDoctrine()->getManager();
            $em->persist($author);
            $em->flush();

            $this->addFlash(
                'notice',
                'Автор добавлен'
            );
            return $this->redirectToRoute('author_list');
        }
        return $this->render('author/create.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/author/edit/{id}", name="author_edit")
     */
    public function editAction($id, Request $request){
        $author = $this->getDoctrine()->getRepository('AppBundle:Author')->find($id);

        $author->setSurname($author->getSurname());
        $author->setName($author->getName());
        $author->setPatronymic($author->getPatronymic());

        $form = $this->createFormBuilder($author)
            ->add('surname', TextType::class, array(
                'label' => 'Фамилия',
                'attr' => array(
                    'class' => 'form-control',
                    'style' => 'margin-bottom:15px')))
            ->add('name', TextType::class, array(
                'label' => 'Имя',
                'attr' => array(
                    'class' => 'form-control',
                    'style' => 'margin-bottom:15px')))
            ->add('patronymic', TextType::class, array(
                'label' => 'Отчество',
                'required' => false,
                'attr' => array(
                    'class' => 'form-control',
                    'style' => 'margin-bottom:15px')))
            ->add('Save', SubmitType::class, array(
                'label' => 'Внести изменения',
                'attr' => array(
                    'class' => 'btn btn-success',
                    'style' => 'margin-bottom:15px')))
            ->getForm();
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            //get data
            $surname = $form['surname']->getData();
            $name = $form['name']->getData();
            $patronymic = $form['patronymic']->getData();

            $em = $this->getDoctrine()->getManager();
            $author = $em->getRepository('AppBundle:Author')->find($id);

            $author->setSurname($surname);
            $author->setName($name);
            $author->setPatronymic($patronymic);

            $em->flush();

            $this->addFlash(
                'notice',
                'Информация об авторе изменена'
            );
            return $this->redirectToRoute('author_list');
        }

        return $this->render('author/edit.html.twig', array('author' => $author, 'form' => $form->createView()));
    }

    /**
     * @Route("/author/remove/{id}", name="author_remove")
     */
    //TODO test
    public function removeAction($id, Request $request){
        $em = $this->getDoctrine()->getManager();
        $author = $em->getRepository('AppBundle:Author')->find($id);

        $em->remove($author);
        $em->flush();

        $this->addFlash(
            'notice',
            'Автор удален из каталога'
        );
        return $this->redirectToRoute('author_list');
    }
}