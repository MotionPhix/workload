<?php

namespace App\Exports;

use App\Models\Brand;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BrandAnalyticsExport implements FromCollection, WithHeadings
{
  protected $brand;

  public function __construct(Brand $brand)
  {
    $this->brand = $brand;
  }

  public function collection()
  {
    return collect([
      [
        'Total Projects' => $this->brand->projects->count(),
        'Total Tasks' => $this->brand->projects->flatMap->tasks->count(),
        'Completed Tasks' => $this->brand->projects->flatMap->tasks->where('status', 'done')->count(),
      ],
    ]);
  }

  public function headings(): array
  {
    return ['Total Projects', 'Total Tasks', 'Completed Tasks'];
  }
}
