<?php

namespace Loevgaard\DandomainAltapayBundle\Form;

use Loevgaard\DandomainAltapayBundle\Entity\SiteSetting;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SiteSettingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setMethod('POST')
            ->add('siteId', null, [
                'label' => 'site_setting.label.site_id',
            ])
            ->add('setting', ChoiceType::class, [
                'label' => 'site_setting.label.setting',
                'choices' => SiteSetting::getSettings(),
                'choice_label' => function ($value, $key, $index) {
                    return SiteSetting::SETTING_TRANSLATION_PREFIX.$key;
                },
            ])
            ->add('val', TextareaType::class, [
                'label' => 'site_setting.label.val',
            ])
            /*
             * Save button
             */
            ->add('save', SubmitType::class, [
                'label' => 'layout.save',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SiteSetting::class,
            'translation_domain' => 'LoevgaardDandomainAltapayBundle',
        ]);
    }
}
