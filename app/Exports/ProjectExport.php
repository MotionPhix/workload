<?php

namespace App\Exports;

use App\Models\Project;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProjectExport implements FromCollection, WithHeadings
{
  protected $filters;

  public function __construct($filters = [])
  {
    $this->filters = $filters;
  }

  public function collection()
  {
    return Project::query()
      ->when($this->filters['status'], fn($query) => $query->where('status', $this->filters['status']))
      ->when($this->filters['start_date'], fn($query) => $query->where('start_date', '>=', $this->filters['start_date']))
      ->when($this->filters['end_date'], fn($query) => $query->where('end_date', '<=', $this->filters['end_date']))
      ->get(['name', 'status', 'start_date', 'end_date']);
  }

  public function headings(): array
  {
    return ['Name', 'Status', 'Start Date', 'End Date'];
  }
}
