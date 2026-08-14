<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Payment Receipt — {{ $transaction->ticket_number }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #1a1a2e; background: #fff; }
        .page { padding: 30px 40px; max-width: 700px; margin: 0 auto; }
        .header { border-bottom: 3px solid #00d492; padding-bottom: 20px; margin-bottom: 30px; }
        .header h1 { font-size: 22px; color: #00d492; letter-spacing: 0.5px; }
        .header p { color: #666; margin-top: 4px; font-size: 11px; }
        .section { margin-bottom: 24px; }
        .section h2 { font-size: 13px; font-weight: bold; text-transform: uppercase;
                      letter-spacing: 0.8px; color: #555; border-bottom: 1px solid #eee;
                      padding-bottom: 6px; margin-bottom: 12px; }
        .row { display: flex; justify-content: space-between; margin-bottom: 8px; }
        .row .label { color: #666; }
        .row .value { font-weight: bold; text-align: right; }
        .amount-box { background: #f0fdf9; border: 1px solid #00d492; border-radius: 6px;
                      padding: 16px 20px; text-align: center; margin: 20px 0; }
        .amount-box .amount { font-size: 24px; font-weight: bold; color: #00d492; }
        .amount-box .currency { font-size: 13px; color: #555; margin-top: 4px; }
        .status-badge { display: inline-block; background: #dcfce7; color: #166534;
                         border-radius: 4px; padding: 3px 10px; font-size: 11px; font-weight: bold; }
        .footer { margin-top: 40px; border-top: 1px solid #eee; padding-top: 16px;
                  font-size: 10px; color: #999; text-align: center; }
        table { width: 100%; border-collapse: collapse; }
        td { padding: 6px 0; vertical-align: top; }
        td:last-child { text-align: right; font-weight: bold; }
    </style>
</head>
<body>
<div class="page">
    <div class="header">
        <h1>Konn3ct — Payment Receipt</h1>
        <p>Official receipt for event ticket purchase</p>
    </div>

    <div class="section">
        <h2>Transaction Details</h2>
        <table>
            <tr>
                <td>Ticket Number</td>
                <td>{{ $transaction->ticket_number }}</td>
            </tr>
            <tr>
                <td>Provider Reference</td>
                <td>{{ $transaction->local_reference }}</td>
            </tr>
            <tr>
                <td>Payment Provider</td>
                <td>{{ ucfirst($transaction->provider) }}</td>
            </tr>
            <tr>
                <td>Status</td>
                <td><span class="status-badge">{{ strtoupper($transaction->status) }}</span></td>
            </tr>
            <tr>
                <td>Date</td>
                <td>{{ optional($transaction->paid_at)->format('d M Y, H:i') ?? optional($transaction->created_at)->format('d M Y, H:i') }}</td>
            </tr>
        </table>
    </div>

    <div class="amount-box">
        <div class="amount">{{ $amount_display }}</div>
        <div class="currency">Amount Paid</div>
    </div>

    @if($event)
    <div class="section">
        <h2>Event Details</h2>
        <table>
            @if($event->title)
            <tr><td>Event</td><td>{{ $event->title }}</td></tr>
            @endif
            @if($event->event_date)
            <tr><td>Date</td><td>{{ $event->event_date }}</td></tr>
            @endif
        </table>
    </div>
    @endif

    <div class="footer">
        <p>This is an automatically generated receipt. Keep this document for your records.</p>
        <p style="margin-top:6px;">Konn3ct &bull; {{ config('app.url') }}</p>
    </div>
</div>
</body>
</html>
