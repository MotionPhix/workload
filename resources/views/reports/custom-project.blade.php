<!DOCTYPE html>
<html>
<head>
  <title>Custom Project Report</title>
  <style>
    body { font-family: Arial, sans-serif; }
    h1 { color: #333; }
    table { width: 100%; border-collapse: collapse; }
    th, td { border: 1px solid #ddd; padding: 8px; }
    th { background-color: #f2f2f2; }
  </style>
</head>
<body>
<h1>Custom Project Report</h1>
<table>
  <thead>
  <tr>
    <th>Name</th>
    <th>Status</th>
    <th>Start Date</th>
    <th>End Date</th>
  </tr>
  </thead>
  <tbody>
  @foreach ($projects as $project)
    <tr>
      <td>{{ $project->name }}</td>
      <td>{{ $project->status }}</td>
      <td>{{ $project->start_date }}</td>
      <td>{{ $project->end_date }}</td>
    </tr>
  @endforeach
  </tbody>
</table>
</body>
</html>
