<?php

namespace App\Services;

use App\Models\EventTransaction;
use App\Models\PreRegModel;
use App\Models\PreRegUserModel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

/**
 * PdfGenerationService
 *
 * Generates PDF receipts and event tickets with embedded QR codes.
 *
 * Output files are stored under the 'receipts' and 'tickets' storage disks.
 * No PII is included in file names — only the ticket_number (non-sequential,
 * non-enumerable).
 *
 * IMPORTANT: The QR verify URL uses a signed URL or HMAC-authenticated opaque
 * token, NOT the raw ticket number alone. This prevents enumeration.
 */
class PdfGenerationService
{
    /**
     * Generate a receipt PDF for the given transaction.
     *
     * @param  EventTransaction $transaction
     * @return string  Storage path of the generated file
     */
    public function generateReceipt(EventTransaction $transaction): string
    {
        $event    = $transaction->event ?? PreRegModel::find($transaction->event_id);
        $user     = $transaction->user;
        $snapshot = $transaction->getDecryptedPricingSnapshot();

        $data = [
            'transaction'    => $transaction,
            'event'          => $event,
            'user'           => $user,
            'amount_display' => $this->formatAmount($transaction->amount_minor, $transaction->currency),
            'snapshot'       => $snapshot,
        ];

        $pdf  = Pdf::loadView('pdf.receipt', $data);
        $path = 'receipts/' . $transaction->ticket_number . '-receipt.pdf';

        Storage::put($path, $pdf->output());

        return $path;
    }

    /**
     * Generate a ticket PDF for the given transaction.
     * Includes a QR code that encodes the signed verification URL.
     *
     * @param  EventTransaction $transaction
     * @return string  Storage path of the generated file
     */
    public function generateTicket(EventTransaction $transaction): string
    {
        $event       = $transaction->event ?? PreRegModel::find($transaction->event_id);
        $verifyUrl   = $this->buildSignedVerifyUrl($transaction->ticket_number);
        $qrCodeData  = $this->generateQrCodeDataUri($verifyUrl);

        $data = [
            'transaction' => $transaction,
            'event'       => $event,
            'qr_data_uri' => $qrCodeData,
            'verify_url'  => $verifyUrl,
        ];

        $pdf  = Pdf::loadView('pdf.ticket', $data);
        $path = 'tickets/' . $transaction->ticket_number . '-ticket.pdf';

        Storage::put($path, $pdf->output());

        return $path;
    }

    /**
     * Build a cryptographically signed verification URL for a ticket.
     * Uses Laravel URL signing — the URL is non-enumerable and tamper-resistant.
     * Returns only: validity status, event title, event date.
     *
     * @param  string $ticketNumber
     * @return string
     */
    public function buildSignedVerifyUrl(string $ticketNumber): string
    {
        return \Illuminate\Support\Facades\URL::signedRoute(
            'tickets.verify',
            ['number' => $ticketNumber]
        );
    }

    /**
     * Generate a QR code as a data URI (PNG base64) for embedding in PDF.
     *
     * @param  string $data  The URL or string to encode
     * @return string  data:image/png;base64,...
     */
    public function generateQrCodeDataUri(string $data): string
    {
        $options = new QROptions([
            'outputType' => \chillerlan\QRCode\Output\QROutputInterface::GDIMAGE_PNG,
            'scale'      => 5,
        ]);

        $qrcode = new QRCode($options);
        $image  = $qrcode->render($data);

        // $image is base64-encoded PNG from the library
        if (str_starts_with($image, 'data:')) {
            return $image;
        }

        return 'data:image/png;base64,' . base64_encode($image);
    }

    /**
     * Format a minor-unit amount for display.
     *
     * @param  int    $amountMinor
     * @param  string $currency
     * @return string  e.g. "NGN 5,000.00"
     */
    private function formatAmount(int $amountMinor, string $currency): string
    {
        $major = $amountMinor / 100;
        return strtoupper($currency) . ' ' . number_format($major, 2);
    }
}
