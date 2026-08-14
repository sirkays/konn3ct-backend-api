<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Your Event Ticket</title>
    <style>
        body { font-family: Arial, Helvetica, sans-serif; font-size: 14px; color: #333; background: #f9f9f9; margin: 0; padding: 0; }
        .email-container { max-width: 600px; margin: 30px auto; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.08); }
        .header { background: #00d492; color: #fff; padding: 28px 32px; }
        .header h1 { font-size: 22px; margin: 0; }
        .header p { margin: 6px 0 0; opacity: 0.9; font-size: 13px; }
        .body { padding: 28px 32px; }
        .body p { line-height: 1.6; margin-bottom: 16px; }
        .ticket-box { background: #f0fdf9; border: 1px solid #00d492; border-radius: 6px; padding: 16px 20px; margin: 20px 0; }
        .ticket-box .number { font-size: 20px; font-weight: bold; letter-spacing: 2px; color: #00d492; }
        .ticket-box .label { font-size: 11px; text-transform: uppercase; color: #888; }
        .event-info { margin: 20px 0; }
        .event-info strong { display: block; color: #555; font-size: 12px; text-transform: uppercase; margin-bottom: 4px; }
        .footer { background: #f5f5f5; padding: 16px 32px; font-size: 11px; color: #999; text-align: center; }
        .footer a { color: #00d492; text-decoration: none; }
    </style>
</head>
<body>
<div class="email-container">
    <div class="header">
        <h1>Your ticket is confirmed! 🎉</h1>
        <p>Thank you for your purchase on Konn3ct.</p>
    </div>

    <div class="body">
        <p>Hi there,</p>
        <p>Your payment has been successfully processed and your event ticket is ready. Please find your ticket and receipt attached to this email as PDF files.</p>

        <div class="ticket-box">
            <div class="label">Ticket Number</div>
            <div class="number">{{ $ticket_number }}</div>
        </div>

        @if($event)
        <div class="event-info">
            @if($event->title)
            <strong>Event</strong>
            <span>{{ $event->title }}</span>
            @endif
            @if($event->event_date)
            <strong style="margin-top:10px;">Date</strong>
            <span>{{ $event->event_date }}</span>
            @endif
        </div>
        @endif

        <p>Keep your ticket safe — you may need to present it or show the QR code at the event.</p>
        <p>If you have any questions, please contact our support team.</p>
        <p>See you at the event!<br><strong>The Konn3ct Team</strong></p>
    </div>

    <div class="footer">
        <p>This email was sent to you because you purchased a ticket on <a href="{{ config('app.url') }}">Konn3ct</a>.</p>
        <p>Please do not reply directly to this email.</p>
    </div>
</div>
</body>
</html>
