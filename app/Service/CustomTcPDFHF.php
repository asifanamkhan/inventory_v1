<?php

namespace App\Service;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use TCPDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class CustomTcPDFHF extends TCPDF
{
    // Page footer
    public function Footer()
    {
        // Set position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', 'I', 8);

        // Left-aligned text
        $leftText = 'Design & Developed By: InfoTech IT Solutions, www.infotechitsolutionsbd.com ';
        $this->Cell(0, 10, $leftText, 0, 0, 'L'); // 'L' for left alignment
        // Page number
        $pageText = 'Page ' . $this->getAliasNumPage() . ' of ' . $this->getAliasNbPages();
        // Print the page number
        $this->Cell(0, 10, $pageText, 0, 0, 'R'); // 'R' for right alignment
    }

    // Page header
    public function Header()
    {

        $company = DB::table('HRM_COMPANY_INFO')->first();
        $logo = '';


        if($company->logo){
            $logo = env('IMAGE_PATH').json_decode($company->logo)[0];
        }
        // dd($logo);
        $date= Carbon::now()->toDateString();
        $html = view(
            'livewire.dashboard.reports.helper.pdf-header',
            [
                'company' => $company,
                'headerTitle' => ReportTypeCheck::route(Route::currentRouteName()),
                'logo' => $logo,
                'date' => $date,
            ]
        )
            ->render();

        // Output the HTML content
        $this->writeHTML($html, true, false, true, false, '');
    }
}