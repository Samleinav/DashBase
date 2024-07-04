<?php
namespace Botble\Dashplugin\Abstract\Front\Table;

use Botble\Table\Abstracts\TableAbstract;
use Botble\Base\Facades\Assets;
use Botble\Dashplugin\Facades\DashHelper;
use Botble\Theme\Facades\Theme;
use Illuminate\Support\Arr;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Symfony\Component\HttpFoundation\Response;

class TableFrontAbstract extends TableAbstract
{
    public function booted(): void
    {
        $this
            ->setView(DashHelper::viewPath('table.base'))
            ->bulkChangeUrl(route('table.table.bulk-change.save'))
            ->bulkChangeDataUrl(route('table.table.bulk-change.data'))
            ->bulkActionDispatchUrl(route('table.table.bulk-action.dispatch'))
            ->filterInputUrl(route('table.table.filter.input'));
    }

    public function renderTable(array $data = [], array $mergeData = []): View|Factory|Response
    {
        DashHelper::renderTable();
        return $this->render($this->view, $data, $mergeData);
    }
    
    public function render(?string $view = null, array $data = [], array $mergeData = [])
    {   
        

        Assets::addScripts(['datatables', 'moment', 'datepicker'])
            ->addStyles(['datatables', 'datepicker'])
            ->addStylesDirectly('vendor/core/core/table/css/table.css')
            //->addStylesDirectly('vendor/core/core/base/css/core.css')
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
        
        return Theme::scope('table.base', $data)->render();
    }
}