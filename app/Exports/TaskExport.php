<?php

namespace App\Exports;

use App\Models\Task;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TaskExport implements FromCollection, WithHeadings
{
  protected $filters;

  public function __construct($filters = [])
  {
    $this->filters = $filters;
  }

  public function collection()
  {
    return Task::query()
      ->when($this->filters['status'], fn($query) => $query->where('status', $this->filters['status']))
      ->when($this->filters['priority'], fn($query) => $query->where('priority', $this->filters['priority']))
      ->when($this->filters['due_date'], fn($query) => $query->where('due_date', $this->filters['due_date']))
      ->get(['title', 'status', 'priority', 'due_date']);
  }

  public function headings(): array
  {
    return ['Title', 'Status', 'Priority', 'Due Date'];
  }
}
