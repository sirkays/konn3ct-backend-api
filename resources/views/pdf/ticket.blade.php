<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Event Ticket — {{ $transaction->ticket_number }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #1a1a2e; background: #fff; }
        .ticket { max-width: 600px; margin: 30px auto; border: 2px solid #00d492; border-radius: 12px; overflow: hidden; }
        .ticket-header { background: #00d492; color: #fff; padding: 24px 28px; }
        .ticket-header h1 { font-size: 20px; letter-spacing: 0.5px; }
        .ticket-header p { font-size: 11px; opacity: 0.85; margin-top: 4px; }
        .ticket-body { display: flex; padding: 24px 28px; gap: 20px; }
        .ticket-info { flex: 1; }
        .ticket-qr { width: 120px; flex-shrink: 0; text-align: center; }
        .ticket-qr img { width: 120px; height: 120px; }
        .ticket-qr p { font-size: 9px; color: #888; margin-top: 6px; }
        .field { margin-bottom: 12px; }
        .field .label { font-size: 10px; text-transform: uppercase; letter-spacing: 0.8px; color: #888; }
        .field .value { font-size: 14px; font-weight: bold; margin-top: 2px; }
        .ticket-number { font-size: 18px; letter-spacing: 2px; font-weight: bold; color: #00d492; margin-bottom: 16px; }
        .divider { height: 1px; background: repeating-linear-gradient(to right, #ddd 0, #ddd 8px, transparent 8px, transparent 14px);
                   margin: 0 28px 16px; }
        .ticket-footer { padding: 12px 28px 20px; background: #f8fffe; }
        .ticket-footer p { font-size: 10px; color: #999; }
    </style>
</head>
<body>
<div class="ticket">
    <div class="ticket-header">
        <h1>Konn3ct Event Ticket</h1>
        <p>Present this ticket at the event or show the QR code for verification</p>
    </div>

    <div class="ticket-body">
        <div class="ticket-info">
            <div class="ticket-number">{{ $transaction->ticket_number }}</div>

            @if($event && $event->title)
            <div class="field">
                <div class="label">Event</div>
                <div class="value">{{ $event->title }}</div>
            </div>
            @endif

            @if($event && $event->event_date)
            <div class="field">
                <div class="label">Date</div>
                <div class="value">{{ $event->event_date }}</div>
            </div>
            @endif

            <div class="field">
                <div class="label">Status</div>
                <div class="value" style="color: #00d492;">✓ PAID</div>
            </div>
        </div>

        <div class="ticket-qr">
            @if($qr_data_uri)
                <img src="{{ $qr_data_uri }}" alt="Verification QR Code">
                <p>Scan to verify</p>
            @endif
        </div>
    </div>

    <div class="divider"></div>

    <div class="ticket-footer">
        <p>This is your official event ticket. Keep it safe. Non-transferable.</p>
        <p style="margin-top:4px;">Konn3ct &bull; {{ config('app.url') }}</p>
    </div>
</div>
</body>
</html>
