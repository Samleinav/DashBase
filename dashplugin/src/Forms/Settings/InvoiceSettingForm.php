<?php

namespace Botble\Dashplugin\Forms\Settings;

use Botble\Base\Forms\Fields\GoogleFontsField;
use Botble\Base\Forms\Fields\MediaImageField;
use Botble\Dashplugin\Http\Requests\Settings\InvoiceSettingRequest;
use Botble\Setting\Forms\SettingForm;

class InvoiceSettingForm extends SettingForm
{
    public function setup(): void
    {
        parent::setup();

        $this
            ->setSectionTitle(trans('plugins/dashplugin::settings.invoice.title'))
            ->setSectionDescription(trans('plugins/dashplugin::settings.invoice.description'))
            ->setValidatorClass(InvoiceSettingRequest::class)
            ->addCustomField('googleFonts', GoogleFontsField::class)
            ->add('dash_company_name_for_invoicing', 'text', [
                'label' => trans('plugins/dashplugin::settings.invoicing.company_name'),
                'value' => setting('dash_company_name_for_invoicing', theme_option('site_title')),
            ])
            ->add('dash_company_address_for_invoicing', 'text', [
                'label' => trans('plugins/dashplugin::settings.invoicing.company_address'),
                'value' => setting('dash_company_address_for_invoicing'),
            ])
            ->add('dash_company_email_for_invoicing', 'text', [
                'label' => trans('plugins/dashplugin::settings.invoicing.company_email'),
                'value' => setting('dash_company_email_for_invoicing', get_admin_email()->first()),
            ])
            ->add('dash_company_phone_for_invoicing', 'text', [
                'label' => trans('plugins/dashplugin::settings.invoicing.company_phone'),
                'value' => setting('dash_company_phone_for_invoicing'),
            ])
            ->add('dash_company_logo_for_invoicing', MediaImageField::class, [
                'label' => trans('plugins/dashplugin::settings.invoicing.company_logo'),
                'value' => setting('dash_company_logo_for_invoicing') ?: theme_option('logo'),
                'allow_thumb' => false,
            ])
            ->add('dash_using_custom_font_for_invoice', 'onOffCheckbox', [
                'label' => trans('plugins/dashplugin::settings.using_custom_font_for_invoice'),
                'value' => setting('dash_using_custom_font_for_invoice', false),
                'attr' => [
                    'data-bb-toggle' => 'collapse',
                    'data-bb-target' => '.custom-font-settings',
                ],
            ])
            ->add('open_fieldset_custom_font_settings', 'html', [
                'html' => sprintf(
                    '<fieldset class="form-fieldset custom-font-settings" style="display: %s;" data-bb-value="1">',
                    setting('dash_using_custom_font_for_invoice', false) ? 'block' : 'none'
                ),
            ])
            ->add('dash_invoice_font_family', 'googleFonts', [
                'label' => trans('plugins/dashplugin::settings.invoice_font_family'),
                'selected' => setting('dash_invoice_font_family'),
            ])
            ->add('close_fieldset_custom_font_settings', 'html', [
                'html' => '</fieldset>',
            ])
            ->add('dash_invoice_support_arabic_language', 'onOffCheckbox', [
                'label' => trans('plugins/dashplugin::settings.invoice_support_arabic_language'),
                'value' => setting('dash_invoice_support_arabic_language', false),
            ])
            ->add('dash_enable_invoice_stamp', 'onOffCheckbox', [
                'label' => trans('plugins/dashplugin::settings.enable_invoice_stamp'),
                'value' => setting('dash_enable_invoice_stamp', true),
            ])
            ->add('dash_invoice_code_prefix', 'text', [
                'label' => trans('plugins/dashplugin::settings.invoice_code_prefix'),
                'value' => setting('dash_invoice_code_prefix', 'INV-'),
            ]);
    }
}
