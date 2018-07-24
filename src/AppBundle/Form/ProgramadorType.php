<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProgramadorType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('ci')
                ->add('nombre')
                ->add('apellido1')
                ->add('apellido2')
                ->add('fecha_nacimiento')
                ->add('sexo')
                ->add('certificado')
                ->add('foto_path')
                ->add('sistema_operativo')
                ->add('lenguajes_programacion');
    }

/**
     * {@inheritdoc}
     */

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Programador'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'appbundle_programador';
    }

}
