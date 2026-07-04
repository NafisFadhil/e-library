<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Models\PeminjamanModel;
use App\Models\AnggotaModel;
use App\Libraries\NotificationService;
use App\Models\NotificationModel;

class SendReminders extends BaseCommand
{
    /**
     * The Command's Group
     *
     * @var string
     */
    protected $group = 'Notifications';

    /**
     * The Command's Name
     *
     * @var string
     */
    protected $name = 'notify:reminders';

    /**
     * The Command's Description
     *
     * @var string
     */
    protected $description = 'Send H-3 and H-1 due date email reminders for active peminjaman.';

    /**
     * The Command's Usage
     *
     * @var string
     */
    protected $usage = 'command:name [arguments] [options]';

    /**
     * The Command's Arguments
     *
     * @var array
     */
    protected $arguments = [];

    /**
     * The Command's Options
     *
     * @var array
     */
    protected $options = [];

    /**
     * Actually execute a command.
     *
     * @param array $params
     */
    public function run(array $params)
    {
        CLI::write('Mengecek jadwal pengingat jatuh tempo...', 'yellow');

        $peminjamanModel = new PeminjamanModel();
        $anggotaModel = new AnggotaModel();
        $notificationModel = new NotificationModel();
        $notifService = new NotificationService();

        // Get all active loans
        $activeLoans = $peminjamanModel->where('status_peminjaman', 'Dipinjam')->findAll();

        $today = date('Y-m-d');
        $countSent = 0;

        foreach ($activeLoans as $loan) {
            if (empty($loan['tanggal_jatuh_tempo'])) continue;

            $jatuhTempo = date('Y-m-d', strtotime($loan['tanggal_jatuh_tempo']));
            $diffDays = (strtotime($jatuhTempo) - strtotime($today)) / 86400;

            if ($diffDays == 3 || $diffDays == 1) {
                $jenis = 'H-' . $diffDays . ' Jatuh Tempo';

                // Check if already sent
                $alreadySent = $notificationModel
                    ->where('id_anggota', $loan['id_anggota'])
                    ->where('jenis', $jenis)
                    ->like('isi', 'peminjaman #' . $loan['id_peminjaman'])
                    ->first();

                if (!$alreadySent) {
                    $anggota = $anggotaModel->find($loan['id_anggota']);
                    if ($anggota && !empty($anggota['email'])) {
                        $subject = "Pengingat Jatuh Tempo ($jenis) - E-Library";
                        $message = "Halo {$anggota['nama']},\n\nIni adalah pengingat bahwa peminjaman buku Anda (ID: #{$loan['id_peminjaman']}) akan jatuh tempo dalam {$diffDays} hari pada tanggal " . date('d M Y', strtotime($jatuhTempo)) . ".\n\nMohon segera mengembalikan buku sebelum batas waktu untuk menghindari denda.\n\nTerima kasih.";

                        $success = $notifService->sendNotification(
                            $anggota['id_anggota'],
                            $anggota['email'],
                            $jenis,
                            $subject,
                            $message
                        );

                        if ($success) {
                            CLI::write("-> Mengirim {$jenis} ke {$anggota['email']} (ID Peminjaman: {$loan['id_peminjaman']})", 'green');
                            $countSent++;
                        } else {
                            CLI::write("-> Gagal mengirim {$jenis} ke {$anggota['email']} (ID Peminjaman: {$loan['id_peminjaman']})", 'red');
                        }
                    }
                }
            }
        }

        CLI::write("Selesai. $countSent email pengingat terkirim.", 'green');
    }
}
