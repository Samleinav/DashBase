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
    protected $tabsOpened = false;
    protected $currentTab = '';
    protected $onlySave = false;

    public function __construct()
    {   
        $this->setTitle(__("Actions"));
        $this->setMethod('POST');
        $this->template(DashHelper::viewPath('forms.base'));
        $this->setFormOption('id', strtolower(Str::slug(Str::snake(static::class))));
        $this->setFormOption('class', 'js-base-form');
    }

    public function includeFiles(){
        $this->setFormOptions(['enctype' => 'multipart/form-data']);

        return $this;
    }

    public function onlySave(){
        $this->onlySave = true;
        return $this;
    }

    public function getActionButtons(): string
    {
        if ($this->actionButtons === '') {

            return view('core/base::forms.partials.form-actions',["title"=>$this->title,"onlySave"=>$this->onlySave])->render();
        }

        return $this->actionButtons;
    }

    public function renderFront(array $options = [], bool $showStart = true, bool $showFields = true, bool $showEnd = true)
    {
        Assets::addScripts(['are-you-sure']);

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

    public function tabs($tabs) {
        $tab_id = "tabs-" . strtolower(Str::snake(static::class));
        $html = '<ul class="nav nav-tabs" id="'. $tab_id . '" role="tablist">';
        $first = true;

        foreach ($tabs as $tabId => $tabName) {
            $activeClass = $first ? 'active' : '';
            $html .= '<li class="nav-item" role="presentation">
                        <a class="nav-link ' . $activeClass . '" id="' . $tabId . '" data-bs-toggle="tab" href="#' . $tabId . '-content" role="tab" aria-controls="' . $tabId . '-content" aria-selected="true">' . $tabName . '</a>
                      </li>';
            $first = false;
        }

        $html .= '</ul><div class="tab-content" id="customerTabsContent">';

        $this->tabsOpened = true;
        $this->currentTab = '';

        return $this->add('openTabs', 'html', ['html' => $html]);
    }

    public function openTab($tabId) {
        $activeClass = $this->currentTab === '' ? 'show active' : 'fade';
        $html = '<div class="tab-pane ' . $activeClass . '" id="' . $tabId . '-content" role="tabpanel" aria-labelledby="' . $tabId . '">';
        $this->currentTab = $tabId;

        return $this->add('openTab_' . $tabId, 'html', ['html' => $html]);
    }

    public function closeTab() {
        $html = '</div>';

        return $this->add('closeTab_' . $this->currentTab, 'html', ['html' => $html]);
    }

    public function closeTabs() {
        $html = '</div>';

        $this->tabsOpened = false;
        $this->currentTab = '';

        return $this->add('closeTabs', 'html', ['html' => $html]);
    }


}
