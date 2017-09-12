<?php
namespace Loevgaard\DandomainAltapayBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class TestType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setMethod('POST')
            /**
             * General API fields
             */
            ->add('APIkey', null, [
                'label' => 'APIkey',
                'required' => false
            ])
            ->add('APIMerchant', null, [
                'label' => 'APIMerchant',
                'required' => false
            ])
            ->add('APIOrderID', null, [
                'label' => 'APIOrderID',
                'required' => false
            ])
            ->add('APISessionID', null, [
                'label' => 'APISessionID',
                'required' => false
            ])
            ->add('APICurrencySymbol', null, [
                'label' => 'APICurrencySymbol',
                'required' => false,
                'attr' => [
                    'placeholder' => 'i.e. DKK, EUR, USD etc.'
                ],
            ])
            ->add('APITotalAmount', null, [
                'label' => 'APITotalAmount',
                'required' => false
            ])
            ->add('APICallBackUrl', null, [
                'label' => 'APICallBackUrl',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Without http://'
                ],
            ])
            ->add('APIFullCallBackOKUrl', null, [
                'label' => 'APIFullCallBackOKUrl',
                'required' => false,
                'attr' => [
                    'placeholder' => 'With http://'
                ],
            ])
            ->add('APICallBackOKUrl', null, [
                'label' => 'APICallBackOKUrl',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Without http://'
                ],
            ])
            ->add('APICallBackServerUrl', null, [
                'label' => 'APICallBackServerUrl',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Without http://'
                ],
            ])
            ->add('APILanguageID', null, [
                'label' => 'APILanguageID',
                'required' => false
            ])
            ->add('APITestMode', null, [
                'label' => 'APITestMode',
                'required' => false,
                'attr' => [
                    'placeholder' => "'False' or 'True'"
                ],
            ])
            ->add('APIPayGatewayCurrCode', null, [
                'label' => 'APIPayGatewayCurrCode',
                'required' => false,
                'attr' => [
                    'placeholder' => 'ISO 4217 currency code. DKK = 208, EUR = 978'
                ],
            ])
            ->add('APICardTypeID', null, [
                'label' => 'APICardTypeID',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Not used with Altapay. You can input 0'
                ],
            ])
            ->add('APIShippingMethod', null, [
                'label' => 'APIShippingMethod',
                'required' => false,
            ])
            ->add('APIShippingFee', null, [
                'label' => 'APIShippingFee',
                'required' => false,
            ])
            ->add('APIPayMethod', null, [
                'label' => 'APIPayMethod',
                'required' => false,
            ])
            ->add('APIPayFee', null, [
                'label' => 'APIPayFee',
                'required' => false,
            ])
            ->add('APILoadBalancerRealIP', null, [
                'label' => 'APILoadBalancerRealIP',
                'required' => false,
            ])
            /**
             * Customer fields
             */
            ->add('APICRekvNr', null, [
                'label' => 'APICRekvNr',
                'required' => false
            ])
            ->add('APICName', null, [
                'label' => 'APICName',
                'required' => false
            ])
            ->add('APICCompany', null, [
                'label' => 'APICCompany',
                'required' => false
            ])
            ->add('APICAddress', null, [
                'label' => 'APICAddress',
                'required' => false
            ])
            ->add('APICAddress2', null, [
                'label' => 'APICAddress2',
                'required' => false
            ])
            ->add('APICZipCode', null, [
                'label' => 'APICZipCode',
                'required' => false
            ])
            ->add('APICCity', null, [
                'label' => 'APICCity',
                'required' => false
            ])
            ->add('APICCountryID', null, [
                'label' => 'APICCountryID',
                'required' => false
            ])
            ->add('APICCountry', null, [
                'label' => 'APICCountry',
                'required' => false
            ])
            ->add('APICPhone', null, [
                'label' => 'APICPhone',
                'required' => false
            ])
            ->add('APICFax', null, [
                'label' => 'APICFax',
                'required' => false
            ])
            ->add('APICEmail', null, [
                'label' => 'APICEmail',
                'required' => false
            ])
            ->add('APICNote', null, [
                'label' => 'APICNote',
                'required' => false
            ])
            ->add('APICcvrnr', null, [
                'label' => 'APICcvrnr',
                'required' => false
            ])
            ->add('APICCustTypeID', null, [
                'label' => 'APICCustTypeID',
                'required' => false
            ])
            ->add('APICEAN', null, [
                'label' => 'APICEAN',
                'required' => false
            ])
            ->add('APICres1', null, [
                'label' => 'APICres1',
                'required' => false
            ])
            ->add('APICres2', null, [
                'label' => 'APICres2',
                'required' => false
            ])
            ->add('APICres3', null, [
                'label' => 'APICres3',
                'required' => false
            ])
            ->add('APICres4', null, [
                'label' => 'APICres4',
                'required' => false
            ])
            ->add('APICres5', null, [
                'label' => 'APICres5',
                'required' => false
            ])
            ->add('APICIP', null, [
                'label' => 'APICIP',
                'required' => false
            ])
            /**
             * Delivery fields
             */
            ->add('APIDName', null, [
                'label' => 'APIDName',
                'required' => false
            ])
            ->add('APIDCompany', null, [
                'label' => 'APIDCompany',
                'required' => false
            ])
            ->add('APIDAddress', null, [
                'label' => 'APIDAddress',
                'required' => false
            ])
            ->add('APIDAddress2', null, [
                'label' => 'APIDAddress2',
                'required' => false
            ])
            ->add('APIDZipCode', null, [
                'label' => 'APIDZipCode',
                'required' => false
            ])
            ->add('APIDCity', null, [
                'label' => 'APIDCity',
                'required' => false
            ])
            ->add('APIDCountryID', null, [
                'label' => 'APIDCountryID',
                'required' => false
            ])
            ->add('APIDCountry', null, [
                'label' => 'APIDCountry',
                'required' => false
            ])
            ->add('APIDPhone', null, [
                'label' => 'APIDPhone',
                'required' => false
            ])
            ->add('APIDFax', null, [
                'label' => 'APIDFax',
                'required' => false
            ])
            ->add('APIDEmail', null, [
                'label' => 'APIDEmail',
                'required' => false
            ])
            ->add('APIDean', null, [
                'label' => 'APIDean',
                'required' => false
            ])
            /**
             * Basket
             */
            ->add('APIBasketProdNumber1', null, [
                'label' => 'APIBasketProdNumber1',
                'required' => false
            ])
            ->add('APIBasketProdName1', null, [
                'label' => 'APIBasketProdName1',
                'required' => false
            ])
            ->add('APIBasketProdAmount1', null, [
                'label' => 'APIBasketProdAmount1',
                'required' => false
            ])
            ->add('APIBasketProdPrice1', null, [
                'label' => 'APIBasketProdPrice1',
                'required' => false
            ])
            ->add('APIBasketProdVAT1', null, [
                'label' => 'APIBasketProdVAT1',
                'required' => false,
                'attr' => [
                    'placeholder' => 'For Denmark input 25'
                ],
            ])
            /**
             * Save button
             */
            ->add('save', SubmitType::class)
        ;
    }

    public function getBlockPrefix()
    {
        return null;
    }

}