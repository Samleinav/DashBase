<?php

namespace Botble\Dashplugin\Tables;

use Botble\Dashplugin\Models\Customer;
use Botble\Dashplugin\Abstract\Front\Table\TableFrontAbstract;
use Botble\Table\Actions\DeleteAction;
use Botble\Table\Actions\EditAction;
use Botble\Table\BulkActions\DeleteBulkAction;
use Botble\Table\Columns\CreatedAtColumn;
use Botble\Table\Columns\EmailColumn;
use Botble\Table\Columns\IdColumn;
use Botble\Table\Columns\NameColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class CustomerTable extends TableFrontAbstract
{
    
    public function setup(): void
    {
        $this
            ->model(Customer::class)
            ->addActions([
                EditAction::make()->route('table.customers.edit'),
                DeleteAction::make()->route('table.customers.destroy'),
            ]);
    }

    public function ajax(): JsonResponse
    {
        $data = $this->table
            ->eloquent($this->query())
            ->filter(function ($query) {
                $keyword = $this->request->input('search.value');
                if ($keyword) {
                    return $query
                        ->where('first_name', 'LIKE', '%' . $keyword . '%')
                        ->orWhere('last_name', 'LIKE', '%' . $keyword . '%')
                        ->orWhere(
                            DB::raw('CONCAT(first_name, " ", last_name)'),
                            'LIKE',
                            '%' . $keyword . '%'
                        )
                        ->orWhere(
                            DB::raw('CONCAT(last_name, " ", first_name)'),
                            'LIKE',
                            '%' . $keyword . '%'
                        );
                }

                return $query;
            });

        return $this->toJson($data);
    }

    public function query(): Relation|Builder|QueryBuilder
    {
        $query = $this
            ->getModel()
            ->query()
            ->select([
                'id',
                'first_name',
                'last_name',
                'email',
                'created_at',
            ]);

        return $this->applyScopes($query);
    }

    public function columns(): array
    {
        return [
            IdColumn::make(),
            NameColumn::make()
                ->route('customer.edit')
                ->orderable(false)
                ->searchable(false),
            EmailColumn::make()->linkable(),
            CreatedAtColumn::make(),
        ];
    }

    public function buttons(): array
    {
        return $this->addCreateButton(route('table.customers.create'));
    }

    public function bulkActions(): array
    {
        return [
            DeleteBulkAction::make()->permission('customer.destroy'),
        ];
    }

    public function getBulkChanges(): array
    {
        return [
            'first_name' => [
                'title' => trans('plugins/hotel::customer.form.first_name'),
                'type' => 'text',
                'validate' => 'required|max:120',
            ],
            'last_name' => [
                'title' => trans('plugins/hotel::customer.form.lst_name'),
                'type' => 'text',
                'validate' => 'required|max:120',
            ],
            'created_at' => [
                'title' => trans('core/base::tables.created_at'),
                'type' => 'datePicker',
            ],
        ];
    }


}
