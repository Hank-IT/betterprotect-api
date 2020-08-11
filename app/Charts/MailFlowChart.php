<?php

declare(strict_types=1);

namespace App\Charts;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Collection;

class MailFlowChart
{
    protected $data;

    protected $start;

    protected $end;

    public function __construct(Collection $data, Carbon $start, Carbon $end)
    {
        $this->data = $data;

        $this->start = $start;

        $this->end = $end;
    }

    public function build(): array
    {
        $labels = $this->createLabels();

        $data = $this->data->maptoGroups(function($item, $key) {
            // Separate emails without status
            if (! isset($item['status'])) {
                return ['unknown' => $item];
            }

            return [$item['status'] => $item];
        })->map(function($data) {
            return $data->map(function($row) {
                return $this->createdReportedAtGroupColumn($row);
            })->groupBy('reported_at_group')->map(function ($row) {
                return $row->count();
            });
        });

        // Fill wholes in the dataset
        $data = $data->map(function($row) use($labels) {
            return $labels->merge($row)->values();
        });

        // Get dataset indexes
        $datasetKeys = $data->keys();

        // Build chart data
        $dataSets = [];
        foreach($datasetKeys as $key) {
            switch($key) {
                case 'reject':
                    $color = '#dc3545';
                    break;
                case 'sent':
                    $color = '#28a745';
                    break;
                case 'deferred':
                    $color = '#ffc107';
                    break;
                default:
                    $color = '#17a2b8';
            }

            $dataSets[] = [
                'fill' => false,
                'label' => trans('postfix.mail.status.' . $key),
                'backgroundColor' => $color,
                'borderColor' => $color,
                'data' => $data[$key],
            ];
        }

        return [
            'labels' => $labels->keys(),
            'datasets' => $dataSets
        ];
    }

    protected function createdReportedAtGroupColumn($row): array
    {
        if (! isset($row['reported_at'])) {
            return $row;
        }

        $group = Carbon::parse($row['reported_at'])->format('Y-m-d H');

        $row['reported_at_group'] = $group . ':00 - ' . $group . ':59';

        return $row;
    }

    protected function createLabels(): Collection
    {
        // Create time period with hours as chart label
        // 2020-03-30 00
        // 2020-03-30 01
        // 2020-03-30 02
        // ...
        $index = CarbonPeriod::create($this->start, $this->end)->hours();

        // Create list of labels:
        // 2020-03-30 00:00 - 2020-03-30 00:59
        // 2020-03-30 01:00 - 2020-03-30 01:59
        // 2020-03-30 02:00 - 2020-03-30 02:59
        // ...
        // We use the label as index instead of the value, because
        // we later merge this array with the data to fill
        // the wholes where 0 emails came in.
        $labels = [];
        foreach($index as $date) {
            $date = $date->format('Y-m-d H');
            $labels[$date . ':00 - ' . $date . ':59'] = 0;
        }

        return collect($labels);
    }
}
