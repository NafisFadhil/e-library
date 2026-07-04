<?php

namespace App\Libraries;

use App\Models\NotificationModel;
use CodeIgniter\Email\Email;
use Config\Services;

class NotificationService
{
    protected Email $email;
    protected NotificationModel $notificationModel;



    public function __construct()
    {
        $this->email = Services::email();
        $this->notificationModel = new NotificationModel();
        
        // Initialize Email Settings from .env
        $config = [
            'protocol'   => 'smtp',
            'SMTPHost'   => getenv('SMTP_HOST'),
            'SMTPUser'   => getenv('SMTP_USER'),
            'SMTPPass'   => getenv('SMTP_PASS'),
            'SMTPPort'   => (int) getenv('SMTP_PORT'),
            'SMTPCrypto' => getenv('SMTP_CRYPTO'),
            'mailType'   => 'html',
            'charset'    => 'utf-8',
            'wordWrap'   => true,
            'CRLF'       => "\r\n",
            'newline'    => "\r\n"
        ];
        
        $this->email->initialize($config);
    }

    /**
     * Send an email notification and save it to the database
     *
     * @param int $idAnggota
     * @param string $recipientEmail
     * @param string $jenis
     * @param string $subject
     * @param string $message
     * @return bool
     */
    public function sendNotification(int $idAnggota, string $recipientEmail, string $jenis, string $subject, string $message): bool
    {
        $this->email->clear();
        
        $fromEmail = getenv('SMTP_USER');
        if (empty($fromEmail) || !filter_var($fromEmail, FILTER_VALIDATE_EMAIL)) {
            $fromEmail = 'admin@elibrary.com';
        }
        
        $this->email->setFrom($fromEmail, 'E-Library System');
        $this->email->setTo($recipientEmail);
        $this->email->setSubject($subject);
        
        // Render HTML Template
        $htmlMessage = view('email_template', [
            'subject' => $subject,
            'message' => nl2br(esc($message))
        ]);
        
        $this->email->setMessage($htmlMessage);

        $status = $this->email->send();
        $statusString = $status ? 'Sukses' : 'Gagal';

        // Log error if failed (for debugging)
        if (!$status) {
            log_message('error', $this->email->printDebugger(['headers']));
        }

        // Save to audit trail
        $this->notificationModel->insert([
            'id_anggota'  => $idAnggota,
            'waktu_kirim' => date('Y-m-d H:i:s'),
            'jenis'       => $jenis,
            'isi'         => $message,
            'status'      => $statusString
        ]);

        return $status;
    }
}
