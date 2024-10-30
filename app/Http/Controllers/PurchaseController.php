<?php

namespace App\Http\Controllers;

use App\Service\ImgToBase64;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Service\GeneratePdf;

class PurchaseController extends Controller
{
    public function invoice($purchase_id)
    {

        $mst = DB::table('INV_PURCHASE_MST as p')
                ->where('p.tran_mst_id', $purchase_id)
                ->leftJoin('INV_SUPPLIER_INFO as s', function ($join) {
                    $join->on('s.p_code', '=', 'p.p_code');
                })
            ->first(['p.*', 's.p_name', 's.address', 's.phone']);


        $dtl = DB::table('INV_PURCHASE_DTL as p')
            ->where('p.tran_mst_id', $purchase_id)
            ->leftJoin('VW_INV_ITEM_DETAILS as pr', function ($join) {
                $join->on('pr.st_group_item_id', '=', 'p.item_code');
            })
            ->get([
                'p.pr_rate',
                'p.vat_amt',
                'p.tot_payble_amt',
                'p.item_qty',
                'p.discount',
                'p.item_code',
                'p.expire_date',
                'pr.item_name',
                'pr.color_name',
                'pr.item_size_name',
                'pr.vat_amt as p_vat_amt'
            ]);


        $data = [
            'ledgers' => $dtl,
            'tran_mst' => $mst
        ];

        $html = view()->make('livewire.dashboard.reports.purchase.purchase-invoice', $data)->render();

        $pdf_data = [
            'html' => $html,
            'filename' => 'product.pdf',
        ];

        GeneratePdf::generate($pdf_data);
    }

    public function returnInvoice($purchase_id)
    {

        $mst = DB::table('INV_PURCHASE_RET_MST as p')
                ->where('p.tran_mst_id', $purchase_id)
                ->leftJoin('INV_SUPPLIER_INFO as s', function ($join) {
                    $join->on('s.p_code', '=', 'p.p_code');
                })
            ->first(['p.*', 's.p_name', 's.address', 's.phone']);


        $dtl = DB::table('INV_PURCHASE_RET_DTL as p')
            ->where('p.tran_mst_id', $purchase_id)
            ->leftJoin('VW_INV_ITEM_DETAILS as pr', function ($join) {
                $join->on('pr.st_group_item_id', '=', 'p.item_code');
            })
            ->get([
                'p.pr_rate',
                'p.vat_amt',
                'p.tot_payble_amt',
                'p.item_qty',
                'p.discount',
                'p.item_code',
                'p.expire_date',
                'pr.item_name',
                'pr.color_name',
                'pr.item_size_name',
                'pr.vat_amt as p_vat_amt'
            ]);


        $data = [
            'ledgers' => $dtl,
            'tran_mst' => $mst
        ];

        $html = view()->make('livewire.dashboard.reports.purchase.purchase-return-invoice', $data)->render();

        $pdf_data = [
            'html' => $html,
            'filename' => 'product-return.pdf',
        ];

        GeneratePdf::generate($pdf_data);
    }

    
    public function InvoioceBackup($purchase_id){
        $company = DB::table('HRM_COMPANY_INFO')->first();
        $base64Logo = '';

        if ($company->logo) {
            $logo = json_decode($company->logo)[0];
            $imagedata = file_get_contents(storage_path('app/upload/company/' . $logo));
            $base64Logo = base64_encode($imagedata);
        }



        $tran_mst = DB::table('INV_PURCHASE_MST as p')
            ->where('p.tran_mst_id', $purchase_id)
            ->leftJoin('INV_SUPPLIER_INFO as s', function ($join) {
                $join->on('s.p_code', '=', 'p.p_code');
            })
            ->first(['p.*', 's.p_name', 's.address', 's.phone']);

        $base64PaymentImg = '';

        if ($tran_mst->payment_status == 'PAID') {
            $imData = file_get_contents(public_path('img/paid.jpg'));
            $base64PaymentImg = base64_encode($imData);
        } else {
            $imData = file_get_contents(public_path('img/due.jpg'));
            $base64PaymentImg = base64_encode($imData);
        }

        $resultDtls = DB::table('INV_PURCHASE_DTL as p')
            ->where('p.tran_mst_id', $purchase_id)
            ->leftJoin('VW_INV_ITEM_DETAILS as pr', function ($join) {
                $join->on('pr.st_group_item_id', '=', 'p.item_code');
            })
            ->get([
                'p.pr_rate',
                'p.vat_amt',
                'p.tot_payble_amt',
                'p.item_qty',
                'p.discount',
                'p.item_code',
                'p.expire_date',
                'pr.item_name',
                'pr.color_name',
                'pr.item_size_name',
                'pr.vat_amt as p_vat_amt'
            ]);

        //    return view('reports.purchase.invoice',compact('company','base64Logo','tran_mst','resultDtls','base64PaymentImg'));
        $pdf = Pdf::loadView('livewire.dashboard.reports.purchase.purchase-invoice', compact(
            'company',
            'base64Logo',
            'tran_mst',
            'resultDtls',
            'base64PaymentImg'
        ));
        return $pdf->stream('purchase-invoice.pdf');
    }
}