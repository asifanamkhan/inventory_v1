<?php

namespace App\Service;

use Illuminate\Support\Facades\Auth;
use App\Service\CustomTcPDFHF;

class GeneratePdf
{
    public static function generate($data){

        $pdf = new CustomTcPDFHF();
        // $pdf = new CustomTcPDFHF('L', 'pt', ['format' => 'A4']);

        // Set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor(Auth::user()->name);
        $pdf->SetTitle($data['filename']);

        // Set margins
        $pdf->SetMargins(10, 52, 10);
        $pdf->SetHeaderMargin(3);
        $pdf->SetFooterMargin(3);

        // Set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        $pdf->AddPage();

        $pdf->writeHTML($data['html'], true, false, true, false, '');

        $pdf->Output(public_path($data['filename']), 'I');
    }
}