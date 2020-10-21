<?php

namespace App\Http\Controllers\Pdf;

use App\Ad;
use App\Developer;
use App\Http\Controllers\ApiController;
use App\Http\Requests\ReportOneRequest;
use App\Http\Requests\ReportThreeRequest;
use App\Http\Requests\ReportTwoRequest;
use App\User;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;

class PdfController extends ApiController
{
    /**
     * GET /api/pdf/reportOne
     * Generate pdf with amount of all Developer Ads and leads or in selected period of time
     *
     * @param ReportOneRequest $request
     * @return \Illuminate\Http\JsonResponse|string
     */
    public function pdfReportOne(ReportOneRequest $request)
    {
        $user = User::findOrFail($request->user_id);
        $user->loadMissing('ads', 'ads.leads');

        $data = [
            'data' => [
                'developer' => $user,
                'ads' => collect(),
                'leads' => collect()
            ]
        ];
        $leads = collect();
        $ads = collect();

        $fromDate = Carbon::createFromTimestamp(strtotime($request->from_date));
        $toDate = Carbon::createFromTimestamp(strtotime($request->to_date));

        foreach ($user->ads as $ad) {
            /* Ads (from_date, to_date) between fromDate and toDate */
            $from_date = Carbon::createFromTimestamp(strtotime($ad->from_date));
            $to_date = Carbon::createFromTimestamp(strtotime($ad->to_date));

            if ($from_date->between($fromDate, $toDate) || $to_date->between($fromDate, $toDate)) {
                $ads->push($ad);
            }

            foreach ($ad['leads'] as $lead) {
                /* Leads (clicked_on) between fromDate and toDate */
                $clicked_on = Carbon::createFromTimestamp(strtotime($lead->clicked_on))->subDay();
                if ($clicked_on->between($fromDate, $toDate)) {
                    $leads->push($lead);
                }
            }
        }

        $data['data']['fromDate'] = Carbon::createFromTimestamp(strtotime($request->from_date))->toDateString();
        $data['data']['toDate'] = Carbon::createFromTimestamp(strtotime($request->to_date))->toDateString();

        $data['data']['ads'] = $ads;
        $data['data']['leads'] = $leads;

        $pdf = PDF::loadView('pdf.reportOne', $data);
        $pdf->setPaper('A4', 'landscape');

        return $pdf->download("user_{$user->id}_ads_leads.pdf");
    }

    /**
     * GET /api/pdf/reportTwo
     * Generate pdf with all Developer Ads
     *
     * @param ReportTwoRequest $request
     * @return \Illuminate\Http\JsonResponse|string
     */
    public function pdfReportTwo(ReportTwoRequest $request)
    {
        $developer = Developer::findOrFail($request->developer_id);
        $developer->loadMissing('ads', 'ads.areas');

        $data = [
            'data' => [
                'developer' => $developer
            ]
        ];

        $pdf = PDF::loadView('pdf.reportTwo', $data);
        $pdf->setPaper('A4', 'landscape');

        return $pdf->download("developer_{$developer->id}_ads.pdf");
    }

    /**
     * GET /api/reportThree
     * Generate pdf with all leads for given ad
     *
     * @param ReportThreeRequest $request
     * @return mixed
     */
    public function pdfReportThree(ReportThreeRequest $request)
    {
        $ad = Ad::findOrFail($request->ad_id);
        $ad->loadMissing('developer');

        $fromDate = Carbon::now()->subYears(10)->toDateTimeString();
        $toDate = Carbon::now()->addYears(10)->toDateTimeString();

        $leads = $ad->leads()->whereBetween('clicked_on', [$fromDate, $toDate])->get();
        $ad['leads'] = $leads;

        $data = [
            'data' => $ad
        ];
        $pdf = PDF::loadView('pdf.reportThree', $data);
        $pdf->setPaper('A4', 'landscape');

        return $pdf->download("ad_{$ad->id}_leads.pdf");
    }
}
