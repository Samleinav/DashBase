<?php

namespace Botble\Dashplugin\Http\Controllers;

use Botble\Base\Facades\Assets;
use Botble\Base\Http\Actions\DeleteResourceAction;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Dashplugin\Facades\InvoiceHelper;
use Botble\Dashplugin\Models\Invoice;
use Botble\Dashplugin\Tables\InvoiceTable;
use Illuminate\Http\Request;

class InvoiceController extends BaseController
{
    public function __construct()
    {
        $this
            ->breadcrumb()
            ->add(trans('plugins/dashplugin::booking.name'))
            ->add(trans('plugins/dashplugin::invoice.name'), route('invoices.index'));
    }

    public function index(InvoiceTable $table)
    {
        $this->pageTitle(trans('plugins/dashplugin::invoice.name'));

        Assets::addScripts(['bootstrap-editable'])
            ->addStyles(['bootstrap-editable']);

        return $table->renderTable();
    }

    public function show(Invoice $invoice)
    {
        $this->pageTitle(trans('plugins/dashplugin::invoice.show', ['code' => $invoice->code]));

        return view('plugins/dashplugin::invoices.show', compact('invoice'));
    }

    public function destroy(Invoice $invoice)
    {
        return DeleteResourceAction::make($invoice);
    }

    public function getGenerateInvoice(int|string $id, Request $request)
    {
        $invoice = Invoice::query()->findOrFail($id);

        if ($request->input('type') === 'print') {
            return InvoiceHelper::streamInvoice($invoice);
        }

        return InvoiceHelper::downloadInvoice($invoice);
    }
}
