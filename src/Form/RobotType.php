<?php

namespace App\Form;

use App\Entity\Robot;
use App\Enum\RobotEnum;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RobotType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, ['label' => 'entity.robot.property.name'])
            ->add('type', ChoiceType::class, [
                'label' => 'entity.robot.property.type',
                'choices' => array_combine(RobotEnum::TYPES, RobotEnum::TYPES)
            ])
            ->add('power', IntegerType::class, ['label' => 'entity.robot.property.power'])
            ->add('save', SubmitType::class, ['label' => 'form.robot.button.save']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Robot::class,
        ]);
    }
}
