<!DOCTYPE html>
<html>
<head>
  <title>Project Report</title>
  <style>
    body { font-family: Arial, sans-serif; }
    h1 { color: #333; }
    table { width: 100%; border-collapse: collapse; }
    th, td { border: 1px solid #ddd; padding: 8px; }
    th { background-color: #f2f2f2; }
  </style>
</head>
<body>
<h1>Project Report: {{ $project->name }}</h1>
<table>
  <tr>
    <th>Description</th>
    <td>{{ $project->description }}</td>
  </tr>
  <tr>
    <th>Status</th>
    <td>{{ $project->status }}</td>
  </tr>
  <tr>
    <th>Start Date</th>
    <td>{{ $project->start_date }}</td>
  </tr>
  <tr>
    <th>End Date</th>
    <td>{{ $project->end_date }}</td>
  </tr>
</table>
</body>
</html>
