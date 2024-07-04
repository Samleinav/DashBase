<?php
namespace Botble\Dashplugin\Abstract\Front\Form;

use Botble\Base\Forms\FormAbstract;
use Botble\Dashplugin\Facades\DashHelper;
use Illuminate\Support\Str;
use Botble\Base\Facades\Assets;
use Botble\Base\Contracts\BaseModel;
use Botble\Base\Events\FormRendering;
use Botble\Base\Events\BeforeCreateContentEvent;
use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Theme\Facades\Theme;

class FormFrontAbstract extends FormAbstract
{

    public function __construct()
    {
        $this->setMethod('POST');
        $this->template(DashHelper::viewPath('forms.base'));
        $this->setFormOption('id', strtolower(Str::slug(Str::snake(static::class))));
        $this->setFormOption('class', 'js-base-form');
    }

    public function renderForm(array $options = [], bool $showStart = true, bool $showFields = true, bool $showEnd = true) : string
    {
        Assets::addScripts(['form-validation', 'are-you-sure']);

        $class = $this->getFormOption('class');
        $this->setFormOption('class', $class . ' dirty-check');

        $model = $this->getModel();

        $this->dispatchBeforeRendering();

        FormRendering::dispatch($this);

        if ($this->getModel() instanceof BaseModel) {
            apply_filters(BASE_FILTER_BEFORE_RENDER_FORM, $this, $this->getModel());
        }

        $this->setupMetadataFields();

        if ($model instanceof BaseModel) {
            if ($model->getKey()) {
                event(new BeforeEditContentEvent($this->request, $model));
            } else {
                event(new BeforeCreateContentEvent($this->request, $model));
            }
        }

        $form = tap(
            $this->renderCustom($options, $showStart, $showFields, $showEnd),
            fn ($rendered) => $this->dispatchAfterRendering($rendered)
        );

        apply_filters(BASE_FILTER_AFTER_RENDER_FORM, $this, $this->getModel());
       
        return $form->render();
    }

    
    
    protected function renderCustom(array $options, bool $showStart, bool $showFields, bool $showEnd)
    {
        $formOptions = $this->buildFormOptionsForFormBuilder(
            $this->formHelper->mergeOptions($this->formOptions, $options)
        );

        $this->setupNamedModel();

        $fields = $this->fields;
        $model = $this->getModel();
        $exclude = $this->exclude;
        $form = $this;
        
        return Theme::scope(
        'forms.base', 
        compact('showStart', 'showFields', 'showEnd', 'formOptions', 'fields', 'model', 'exclude', 'form'), 
        DashHelper::viewPath('forms.base'));
    }

}
