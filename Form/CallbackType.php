<?php

namespace Loevgaard\DandomainAltapayBundle\Form;

use Loevgaard\DandomainAltapayBundle\Manager\CallbackManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CallbackType extends AbstractType
{
    /**
     * @var CallbackManager
     */
    protected $callbackManager;

    public function __construct(CallbackManager $callbackManager)
    {
        $this->callbackManager = $callbackManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('shop_orderid', TextType::class, [
                'property_path' => 'orderId',
                'required' => false,
            ])
            ->add('amount', NumberType::class, [
                'required' => false,
            ])
            ->add('currency', IntegerType::class, [
                'required' => false,
            ])
            ->add('language', TextType::class, [
                'required' => false,
            ])
            ->add('transaction_info', CollectionType::class, [
                'property_path' => 'transactionInfo',
                'required' => false,
            ])
            ->add('status', TextType::class, [
                'required' => false,
            ])
            ->add('error_message', TextType::class, [
                'property_path' => 'errorMessage',
                'required' => false,
            ])
            ->add('merchant_error_message', TextType::class, [
                'property_path' => 'merchantErrorMessage',
                'required' => false,
            ])
            ->add('cardholder_message_must_be_shown', CheckboxType::class, [
                'property_path' => 'cardholderMessageMustBeShown',
                'required' => false,
            ])
            ->add('transaction_id', TextType::class, [
                'property_path' => 'transactionId',
                'required' => false,
            ])
            ->add('type', TextType::class, [
                'required' => false,
            ])
            ->add('payment_status', TextType::class, [
                'property_path' => 'paymentStatus',
                'required' => false,
            ])
            ->add('masked_credit_card', TextType::class, [
                'property_path' => 'maskedCreditCard',
                'required' => false,
            ])
            ->add('blacklist_token', TextType::class, [
                'property_path' => 'blacklistToken',
                'required' => false,
            ])
            ->add('credit_card_token', TextType::class, [
                'property_path' => 'creditCardToken',
                'required' => false,
            ])
            ->add('nature', TextType::class, [
                'required' => false,
            ])
            ->add('require_capture', CheckboxType::class, [
                'property_path' => 'requireCapture',
                'required' => false,
            ])
            ->add('xml', TextType::class, [
                'required' => false,
            ])
            ->add('checksum', TextType::class, [
                'required' => false,
            ])
            ->add('fraud_risk_score', NumberType::class, [
                'property_path' => 'fraudRiskScore',
                'required' => false,
            ])
            ->add('fraud_explanation', TextType::class, [
                'property_path' => 'fraudExplanation',
                'required' => false,
            ])
            ->add('fraud_recommendation', TextType::class, [
                'property_path' => 'fraudRecommendation',
                'required' => false,
            ])
            ->add('avs_code', TextType::class, [
                'property_path' => 'avsCode',
                'required' => false,
            ])
            ->add('avs_text', TextType::class, [
                'property_path' => 'avsText',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->callbackManager->getClass(),
        ));
    }

    public function getBlockPrefix()
    {
        return 'loevgaard_dandomain_altapay_callback';
    }
}
