<?php
namespace Botble\Dashplugin\Abstract\Front\Table;

use Botble\Table\Abstracts\TableAbstract;
use Botble\Base\Facades\Assets;
use Botble\Dashplugin\Facades\DashHelper;
use Botble\Theme\Facades\Theme;
use Google\Protobuf\BoolValue;
use Illuminate\Support\Arr;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Symfony\Component\HttpFoundation\Response;

class TableFrontAbstract extends TableAbstract
{   
    protected string $tableTemplate = '';


    public function setTableTemplate(string $tableTemplate): self
    {
        $this->tableTemplate = $tableTemplate;

        return $this;
    }
    public function booted(): void
    {
        $this
            ->setView(DashHelper::viewPath('table.base'))
            ->setTableTemplate('customer.settings.table')
            ->bulkChangeUrl(route('api.table.bulk-change.save'))
            ->bulkChangeDataUrl(route('api.table.bulk-change.data'))
            ->bulkActionDispatchUrl(route('api.table.bulk-action.dispatch'))
            ->filterInputUrl(route('api.table.filter.input'));
    }

    public function renderTable(array $data = [], array $mergeData = [], bool $useTemplate = false): View|Factory|Response
    {
        if( $this->isAjax() ) {
            return $this->renderAjax();
        }

        DashHelper::renderTable();

        if( $useTemplate ){
            $dataTable = $this->render($this->view, $data, $mergeData, $useTemplate);
            return Theme::scope($this->tableTemplate, compact('dataTable'))->render();
        }
        return $this->render($this->view, $data, $mergeData);
    }
    
    public function render(?string $view = null, array $data = [], array $mergeData = [], bool $useTemplate = false): View|Factory|Response
    {   
        

        Assets::addScripts(['datatables', 'moment', 'datepicker'])
            ->addStyles(['datatables', 'datepicker'])
            ->addStylesDirectly('vendor/core/core/table/css/table.css')
            ->addScriptsDirectly([
                'vendor/core/core/base/libraries/bootstrap3-typeahead.min.js',
                'vendor/core/core/table/js/table.js',
                'vendor/core/core/table/js/filter.js',
            ]);

        if (setting('datatables_pagination_type') == 'dropdown') {
            Assets::addScriptsDirectly(['vendor/core/core/base/libraries/datatables/extensions/Pagination/js/dataTables.pagination.min.js'])
                ->addStylesDirectly(['vendor/core/core/base/libraries/datatables/extensions/Pagination/css/dataTables.pagination.min.css']);
        }

        $data['id'] = Arr::get($data, 'id', $this->getOption('id'));
        $data['class'] = Arr::get($data, 'class', $this->getOption('class'));

        $this->setAjaxUrl($this->ajaxUrl . '?' . http_build_query(request()->input()));

        $this->setOptions($data);

        $data['actions'] = $this->getBulkActions();

        $data['table'] = $this;

        $data["dataTable"] = $this->html();
        // 
        if ($this->request()->ajax() && $this->request()->wantsJson()) {
            return app()->call([$this, 'ajax']);
        }

        /** @var string $action */
        $action = $this->request()->get('action');
        $actionMethod = $action === 'print' ? 'printPreview' : $action;

        if (in_array($action, $this->actions) && method_exists($this, $actionMethod)) {
            /** @var callable $callback */
            $callback = [$this, $actionMethod];

            return app()->call($callback);
        }

        /** @phpstan-ignore-next-line  */

        $data['dataTableVariable'] = $this->dataTableVariable;
        /** @phpstan-ignore-next-line  */
        
        return $useTemplate ? view($this->view, $data) : Theme::scope('table.base', $data)->render() ;
    }

    public function isAjax(): bool
    {
        return $this->request()->ajax() && $this->request()->wantsJson();
    }

    public function renderAjax(){
        return app()->call([$this, 'ajax']);
    }
}