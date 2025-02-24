<!DOCTYPE html>
<html>
<head>
  <title>Custom Task Report</title>
  <style>
    body { font-family: Arial, sans-serif; }
    h1 { color: #333; }
    table { width: 100%; border-collapse: collapse; }
    th, td { border: 1px solid #ddd; padding: 8px; }
    th { background-color: #f2f2f2; }
  </style>
</head>
<body>
<h1>Custom Task Report</h1>
<table>
  <thead>
  <tr>
    <th>Title</th>
    <th>Status</th>
    <th>Priority</th>
    <th>Due Date</th>
  </tr>
  </thead>
  <tbody>
  @foreach ($tasks as $task)
    <tr>
      <td>{{ $task->title }}</td>
      <td>{{ $task->status }}</td>
      <td>{{ $task->priority }}</td>
      <td>{{ $task->due_date }}</td>
    </tr>
  @endforeach
  </tbody>
</table>
</body>
</html>
