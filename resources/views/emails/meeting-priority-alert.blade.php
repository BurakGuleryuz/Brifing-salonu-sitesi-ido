<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
</head>
<body style="font-family: Arial, sans-serif; background:#f4f4f4; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background: #fff; border-radius: 8px; overflow: hidden;">
        <div style="background: #dc3545; color: #fff; padding: 20px; text-align: center;">
            <h2 style="margin:0;">🔴 ACİL TOPLANTI BİLDİRİMİ</h2>
        </div>
        <div style="padding: 25px;">
            <p>Aşağıdaki toplantı <strong>acil</strong> olarak işaretlenmiştir:</p>

            <table style="width: 100%; border-collapse: collapse; margin-top: 15px;">
                <tr>
                    <td style="padding: 8px 0; color: #666; width: 140px;">Başlık:</td>
                    <td style="padding: 8px 0; font-weight: bold;">{{ $meeting->title }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; color: #666;">Oda:</td>
                    <td style="padding: 8px 0;">{{ $meeting->room->name }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; color: #666;">Tarih:</td>
                    <td style="padding: 8px 0;">{{ $meeting->start_time->format('d.m.Y') }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; color: #666;">Saat:</td>
                    <td style="padding: 8px 0;">{{ $meeting->start_time->format('H:i') }} - {{ $meeting->end_time->format('H:i') }}</td>
                </tr>
                @if ($meeting->organizer)
                <tr>
                    <td style="padding: 8px 0; color: #666;">Düzenleyen:</td>
                    <td style="padding: 8px 0;">{{ $meeting->organizer }}</td>
                </tr>
                @endif
            </table>

            <p style="margin-top: 25px; color: #666; font-size: 13px;">
                Bu, Toplantı Yönetim Sistemi tarafından otomatik gönderilen bir bildirimdir.
            </p>
        </div>
    </div>
</body>
</html>