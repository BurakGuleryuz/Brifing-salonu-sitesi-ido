<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="refresh" content="60">
    <title>{{ $room->name }} - Brifing Ekranı</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('images/ido-logo.jpg') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #0B1A26;
            color: #fff;
            font-family: 'Segoe UI', sans-serif;
            height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .header {
            padding: 30px 50px;
            border-bottom: 3px solid #F7941D;
            background-color: #003B5C;
        }
        .room-name {
            font-size: 2.5rem;
            font-weight: bold;
            margin: 0;
        }
        .clock {
            font-size: 1.5rem;
            color: #cfe3f0;
        }
        .main-status {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 40px;
        }
        .status-busy {
            background-color: #4b1113;
        }
        .status-free {
            background-color: #0f3d1f;
        }
        .status-faulty {
            background-color: #664d03;
        }
        .status-label {
            font-size: 3rem;
            font-weight: bold;
        }
        .meeting-title {
            font-size: 2rem;
            margin-top: 10px;
        }
        .meeting-time {
            font-size: 1.3rem;
            color: #e0e0e0;
            margin-top: 10px;
        }
        .upcoming {
            padding: 30px 50px;
            border-top: 3px solid #F7941D;
            background-color: #0B1A26;
        }
        .upcoming h3 {
            color: #F7941D;
            font-size: 1.2rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .upcoming-item {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #1e3040;
            font-size: 1.3rem;
        }
        .upcoming-item:last-child {
            border-bottom: none;
        }
    </style>
</head>
<body>

    <div class="header d-flex justify-content-between align-items-center">
        <h1 class="room-name">{{ $room->name }}</h1>
        <div class="clock">{{ $now->format('d.m.Y') }} — {{ $now->format('H:i') }}</div>
    </div>

    <div class="main-status {{ $room->is_faulty ? 'status-faulty' : ($currentMeeting ? 'status-busy' : 'status-free') }}">
        @if ($room->is_faulty)
            <div class="status-label">⚠️ ARIZALI</div>
            <div class="meeting-time">Bu salon şu anda kullanıma kapalıdır.</div>
            @if ($room->fault_note)
                <div class="meeting-time">{{ $room->fault_note }}</div>
            @endif
        @elseif ($currentMeeting)
            <div class="status-label">🔴 DOLU</div>
            @if ($currentMeeting->type === 'yonetim_kurulu')
                <div class="meeting-title">Özel Toplantı</div>
                <div class="meeting-time">{{ $currentMeeting->start_time->format('H:i') }} - {{ $currentMeeting->end_time->format('H:i') }}</div>
            @else
                <div class="meeting-title">{{ $currentMeeting->title }}</div>
                <div class="meeting-time">
                    {{ $currentMeeting->start_time->format('H:i') }} - {{ $currentMeeting->end_time->format('H:i') }}
                    @if ($currentMeeting->organizer)
                        · {{ $currentMeeting->organizer }}
                    @endif
                </div>
            @endif
        @else
            <div class="status-label">🟢 MÜSAİT</div>
            <div class="meeting-time">Bu oda şu anda kullanılabilir</div>
        @endif
    </div>

    <div class="upcoming">
        <h3>Bugün Sonraki Toplantılar</h3>

        @forelse ($upcomingMeetings as $meeting)
            <div class="upcoming-item">
                <span>{{ $meeting->type === 'yonetim_kurulu' ? 'Özel Toplantı' : $meeting->title }}</span>
                <span>{{ $meeting->start_time->format('H:i') }} - {{ $meeting->end_time->format('H:i') }}</span>
            </div>
        @empty
            <div class="upcoming-item text-muted">
                <span>Bugün için başka toplantı yok.</span>
            </div>
        @endforelse
    </div>

</body>
</html>