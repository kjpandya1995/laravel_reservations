<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $data->name }} - Participants List</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .header { margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $data->name }}</h1>
        <h4>Start time: {{ $data->start_time }}</h4>
        <p><strong>Total Participants: {{ $data->participants->count() }}</strong></p>
    </div>

    @if($data->participants->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Registration Time</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data->participants as $participant)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $participant->name }}</td>
                        <td>{{ $participant->email }}</td>
                        <td>{{ \Carbon\Carbon::parse($participant->pivot->created_at)->format('d M Y, h:i A') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p style="text-align: center; margin-top: 40px; color: #666;">
            No participants registered for this activity yet.
        </p>
    @endif
</body>
</html>