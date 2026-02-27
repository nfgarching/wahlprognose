<?php

namespace App\Http\Controllers;

use App\Models\Forecast;
use App\Models\Party;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ForecastExportController extends Controller
{
    public function __invoke(): StreamedResponse
    {
        abort_unless(auth()->user()->is_admin, 403);

        $parties = Party::orderBy('id')->get();

        $forecasts = Forecast::with([
            'user',
            'mayorCandidate1',
            'mayorCandidate2',
            'mayorRunoffWinner',
            'seats',
        ])->orderBy('created_at')->get();

        $filename = 'prognosen-'.now()->format('Y-m-d').'.csv';

        return response()->streamDownload(function () use ($forecasts, $parties) {
            $handle = fopen('php://output', 'w');

            // UTF-8 BOM für Excel-Kompatibilität
            fwrite($handle, "\xEF\xBB\xBF");

            $headers = [
                'ID',
                'Eingereicht am',
                'Pseudonym',
                'Registriert',
                'Name',
                'E-Mail',
                'BM-Kandidat 1',
                'BM-Kandidat 2',
                'Stichwahl-Favorit',
            ];

            foreach ($parties as $party) {
                $headers[] = 'Sitze '.$party->short_name;
            }

            fputcsv($handle, $headers, ';');

            foreach ($forecasts as $forecast) {
                $seatMap = $forecast->seats->keyBy('party_id');

                $row = [
                    $forecast->id,
                    $forecast->created_at->format('d.m.Y H:i'),
                    $forecast->pseudonym,
                    $forecast->user_id ? 'Ja' : 'Nein',
                    $forecast->user?->name ?? '',
                    $forecast->user?->email ?? '',
                    $forecast->mayorCandidate1?->name ?? '',
                    $forecast->mayorCandidate2?->name ?? '',
                    $forecast->mayorRunoffWinner?->name ?? '',
                ];

                foreach ($parties as $party) {
                    $row[] = $seatMap[$party->id]?->seats ?? 0;
                }

                fputcsv($handle, $row, ';');
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
