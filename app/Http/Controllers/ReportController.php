<?php

namespace App\Http\Controllers;

use App\Jobs\GenerateReport;
use App\Models\Report;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $pending_reports = Report::query()
            ->whereNull('ready_at')
            ->get();

        $reports = Report::query()
            ->orderByDesc('id')
            ->whereNotNull('ready_at')
            ->paginate();

        $reports->transform(function (Report $report) {
            $report->append('file_url');
            return $report;
        });

        return Inertia::render('Reports/ReportsListPage', compact('pending_reports', 'reports'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store()
    {
        $report = Report::create();
        dispatch(new GenerateReport($report));

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Report $report)
    {
        if (! request()->hasValidSignature() || ! $report->ready_at) {
            abort(401);
        }

        return Storage::download($report->file_path);
    }
}
