<!DOCTYPE html>
<html>
<head>
  <title>Brand Analytics Report</title>
  <style>
    body { font-family: Arial, sans-serif; }
    h1 { color: #333; }
    table { width: 100%; border-collapse: collapse; }
    th, td { border: 1px solid #ddd; padding: 8px; }
    th { background-color: #f2f2f2; }
  </style>
</head>
<body>
<h1>Brand Analytics Report: {{ $brand->name }}</h1>
<table>
  <tr>
    <th>Total Projects</th>
    <td>{{ $brand->projects->count() }}</td>
  </tr>
  <tr>
    <th>Total Tasks</th>
    <td>{{ $brand->projects->flatMap->tasks->count() }}</td>
  </tr>
  <tr>
    <th>Completed Tasks</th>
    <td>{{ $brand->projects->flatMap->tasks->where('status', 'done')->count() }}</td>
  </tr>
</table>
</body>
</html>
