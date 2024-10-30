<?php

namespace App\Http\Controllers;
use App\Service\CustomTcPDFHF;
use App\Service\GeneratePdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class ProductController extends Controller
{
    public function productList()
    {
        $products = DB::table('VW_INV_ITEM_DETAILS')->get();
        $data = [
            'products' => $products
        ];

        $html = view()->make('livewire.dashboard.reports.product.product-list', $data)->render();

        $pdf_data = [
            'html' => $html,
            'filename' => 'product-info.pdf',
        ];
        GeneratePdf::generate($pdf_data);
    }

    public function purchaseReport(Request $request)
    {
        $query = DB::table('VW_PRODUCT_PURCHASE_REPORT');

        if ($request->start_date) {
            $query->where('tran_date', '>=', $request->start_date);
        }
        if ($request->end_date) {
            $query->where('tran_date', '<=', $request->end_date);
        }
        if ($request->st_group_item_id) {
            $query->where('st_group_item_id', $request->st_group_item_id);
        }
        if ($request->branch_id) {
            $query->where('branch_id', $request->branch_id);
        }
        if ($request->catagories_id) {
            $query->where('catagories_id', $request->catagories_id);
        }
        $products = $query->get();

        $data = [
            'ledgers' => $products
        ];

        $html = view()->make('livewire.dashboard.reports.product.pdf.product-purchase-report-pdf', $data)->render();

        $pdf_data = [
            'html' => $html,
            'filename' => 'product-purchase.pdf',
        ];
        GeneratePdf::generate($pdf_data);
    }

    public function purchaseReturnReport(Request $request)
    {

        $query = DB::table('VW_PRODUCT_PURCHASE_RETURN');

        if ($request->start_date) {
            $query->where('return_date', '>=', $request->start_date);
        }
        if ($request->end_date) {
            $query->where('return_date', '<=', $request->end_date);
        }
        if ($request->st_group_item_id) {
            $query->where('st_group_item_id', $request->st_group_item_id);
        }
        if ($request->branch_id) {
            $query->where('branch_id', $request->branch_id);
        }
        if ($request->catagories_id) {
            $query->where('catagories_id', $request->catagories_id);
        }
        $products = $query->get();

        $data = [
            'ledgers' => $products
        ];

        $html = view()->make('livewire.dashboard.reports.product.pdf.product-purchase-return-report-pdf', $data)->render();

        $pdf_data = [
            'html' => $html,
            'filename' => 'product-purchase.pdf',
        ];
        GeneratePdf::generate($pdf_data);
    }

    public function purchaseDamageReport(Request $request)
    {
        $query = DB::table('VW_PRODUCT_DAMAGE_REPORT');
        if ($request->start_date) {
            $query->where('damage_date', '>=', $request->start_date);
        }
        if ($request->end_date) {
            $query->where('damage_date', '<=', $request->end_date);
        }
        if ($request->st_group_item_id) {
            $query->where('st_group_item_id', $request->st_group_item_id);
        }
        if ($request->branch_id) {
            $query->where('branch_id', $request->branch_id);
        }
        if ($request->catagories_id) {
            $query->where('catagories_id', $request->catagories_id);
        }
        $products = $query->get();

        $data = [
            'ledgers' => $products
        ];

        $html = view()->make('livewire.dashboard.reports.product.pdf.product-damage-report-pdf', $data)->render();

        $pdf_data = [
            'html' => $html,
            'filename' => 'product-damage.pdf',
        ];

        GeneratePdf::generate($pdf_data);
    }

    public function purchaseExpireReport(Request $request)
    {

        $query = DB::table('VW_PRODUCT_EXPIRE_REPORT');
        if ($request->start_date) {
            $query->where('expire_date', '>=', $request->start_date);
        }
        if ($request->end_date) {
            $query->where('expire_date', '<=', $request->end_date);
        }
        if ($request->st_group_item_id) {
            $query->where('st_group_item_id', $request->st_group_item_id);
        }
        if ($request->branch_id) {
            $query->where('branch_id', $request->branch_id);
        }
        if ($request->catagories_id) {
            $query->where('catagories_id', $request->catagories_id);
        }
        $products = $query->get();

        $data = [
            'ledgers' => $products
        ];

        $html = view()->make('livewire.dashboard.reports.product.pdf.product-expire-report-pdf', $data)->render();

        $pdf_data = [
            'html' => $html,
            'filename' => 'product-expire.pdf',
        ];

        GeneratePdf::generate($pdf_data);
    }

    public function purchaseStockReport(Request $request)
    {
        $query = DB::table('VW_INV_ITEM_STOCK_QTY');

        if ($request->st_group_item_id) {
            $query->where('st_group_item_id', $request->st_group_item_id);
        }
        if ($request->branch_id) {
            $query->where('branch_id', $request->branch_id);
        }
        if ($request->catagories_id) {
            $query->where('catagories_id', $request->catagories_id);
        }
        $products = $query->get();

        $data = [
            'ledgers' => $products
        ];

        $html = view()->make('livewire.dashboard.reports.product.pdf.product-stock-report-pdf', $data)->render();

        $pdf_data = [
            'html' => $html,
            'filename' => 'product-stock.pdf',
        ];
        GeneratePdf::generate($pdf_data);
    }

    public function productSaleReport(Request $request){

        $query = DB::table('VW_SALES_REPORT');

        if ($request->start_date) {
            $query->where('sales_date', '>=', $request->start_date);
        }
        if ($request->end_date) {
            $query->where('sale_date', '<=', $request->end_date);
        }
        if ($request->st_group_item_id) {
            $query->where('st_group_item_id', $request->st_group_item_id);
        }
        if ($request->branch_id) {
            $query->where('branch_id', $request->branch_id);
        }
        if ($request->catagories_id) {
            $query->where('catagories_id', $request->catagories_id);
        }
        $products = $query->get();

        $data = [
            'ledgers' => $products
        ];

        $html = view()->make('livewire.dashboard.reports.product.pdf.product-sale-report-pdf', $data)->render();

        $pdf_data = [
            'html' => $html,
            'filename' => 'product-sale.pdf',
        ];

        GeneratePdf::generate($pdf_data);
    }

    public function productSaleReturnReport(Request $request){

        $query = DB::table('VW_SALES_RETURN_REPORT');

        if ($request->start_date) {
            $query->where('return_date', '>=', $request->start_date);
        }
        if ($request->end_date) {
            $query->where('return_date', '<=', $request->end_date);
        }
        if ($request->st_group_item_id) {
            $query->where('st_group_item_id', $request->st_group_item_id);
        }
        if ($request->branch_id) {
            $query->where('branch_id', $request->branch_id);
        }
        if ($request->catagories_id) {
            $query->where('catagories_id', $request->catagories_id);
        }
        $products = $query->get();

        $data = [
            'ledgers' => $products
        ];

        $html = view()->make('livewire.dashboard.reports.product.pdf.product-sale-return-report-pdf', $data)->render();

        $pdf_data = [
            'html' => $html,
            'filename' => 'product-return-sale.pdf',
        ];

        GeneratePdf::generate($pdf_data);
    }

}