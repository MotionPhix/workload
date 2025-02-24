<!DOCTYPE html>
<html>
<head>
  <title>Task Report</title>
  <style>
    body { font-family: Arial, sans-serif; }
    h1 { color: #333; }
    table { width: 100%; border-collapse: collapse; }
    th, td { border: 1px solid #ddd; padding: 8px; }
    th { background-color: #f2f2f2; }
  </style>
</head>
<body>
<h1>Task Report: {{ $task->title }}</h1>
<table>
  <tr>
    <th>Description</th>
    <td>{{ $task->description }}</td>
  </tr>
  <tr>
    <th>Status</th>
    <td>{{ $task->status }}</td>
  </tr>
  <tr>
    <th>Priority</th>
    <td>{{ $task->priority }}</td>
  </tr>
  <tr>
    <th>Due Date</th>
    <td>{{ $task->due_date }}</td>
  </tr>
</table>
</body>
</html>
