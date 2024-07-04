<?php

namespace Botble\Hotel\Forms\Settings;

use Botble\Base\Forms\FieldOptions\CheckboxFieldOption;
use Botble\Base\Forms\FieldOptions\NumberFieldOption;
use Botble\Base\Forms\Fields\NumberField;
use Botble\Base\Forms\Fields\OnOffCheckboxField;
use Botble\Base\Forms\FormCollapse;
use Botble\Hotel\Facades\HotelHelper;
use Botble\Hotel\Http\Requests\Settings\GeneralSettingRequest;
use Botble\Setting\Forms\SettingForm;

class GeneralSettingForm extends SettingForm
{
    public function setup(): void
    {
        parent::setup();

        $this
            ->setSectionTitle(trans('plugins/hotel::settings.general.title'))
            ->setSectionDescription(trans('plugins/hotel::settings.general.description'))
            ->setValidatorClass(GeneralSettingRequest::class)
            ->addCollapsible(
                FormCollapse::make('hotel_general_settings')
                    ->targetField(
                        'hotel_enable_booking',
                        OnOffCheckboxField::class,
                        CheckboxFieldOption::make()
                            ->value(HotelHelper::isBookingEnabled())
                            ->label(trans('plugins/hotel::settings.general.enable_booking'))
                    )
                    ->fieldset(function (GeneralSettingForm $form) {
                        $minimumNumberOfGuests = HotelHelper::getMinimumNumberOfGuests();
                        $maximumNumberOfGuests = HotelHelper::getMaximumNumberOfGuests();

                        return $form
                            ->add(
                                'hotel_minimum_number_of_guests',
                                NumberField::class,
                                NumberFieldOption::make()
                                    ->attributes([
                                        'min' => 1,
                                        'max' => old('hotel_maximum_number_of_guests', $maximumNumberOfGuests),
                                    ])
                                    ->value($minimumNumberOfGuests)
                                    ->label(trans('plugins/hotel::settings.general.minimum_number_of_guests'))
                            )
                            ->add(
                                'hotel_maximum_number_of_guests',
                                NumberField::class,
                                NumberFieldOption::make()
                                    ->attributes([
                                        'min' => old('hotel_minimum_number_of_guests', $minimumNumberOfGuests),
                                    ])
                                    ->value($maximumNumberOfGuests)
                                    ->label(trans('plugins/hotel::settings.general.maximum_number_of_guests'))
                            );
                    })
                    ->isOpened(old('hotel_enable_booking', HotelHelper::isBookingEnabled()))
            );
    }
}
