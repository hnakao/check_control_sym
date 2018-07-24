<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ChequesType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('checkNumber')
                ->add('beneficiary')
                ->add('concept')
                ->add('notes')
                ->add('bancoId')
                ->add('emicionDate', DateType::class, array(
                    "label" => "Fecha de Nacimiento",
                    "widget" => "single_text",
                    "format" => "yyyy-MM-dd",
                    "attr" => array('class' => 'datepicker')
                ))
                ->add('atDate', DateType::class, array('required' => false,
                    "label" => "Fecha de Nacimiento",
                    "widget" => "single_text",
                    "format" => "yyyy-MM-dd",
                    "attr" => array('class' => 'datepicker')))
                ->add('postDate', DateType::class, array('required' => false,
                    "label" => "Fecha de Nacimiento",
                    "widget" => "single_text",
                    "format" => "yyyy-MM-dd",
                    "attr" => array('class' => 'datepicker')))
                ->add('estado')
                ->add('pagado', ChoiceType::class, array(
                    'choices' => array(
                        'No' => 'no',
                        'Si' => 'si',
                        
            )))
                ->add('valor')
                ->add('observaciones')
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Cheques'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'appbundle_cheques';
    }

}
