<?php

namespace App\Console\Commands;

use App\Mail\DonationReminder;
use App\Models\Donation;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

/**
 * Rappel mensuel des dons réguliers (phase 1 : notification seulement, pas de prélèvement).
 * N'envoie qu'aux donateurs non anonymes dont le contact est une adresse e-mail.
 */
class SendMonthlyDonationReminders extends Command
{
    protected $signature = 'donations:monthly-reminder';

    protected $description = 'Envoie le rappel mensuel aux donateurs réguliers.';

    public function handle(): int
    {
        $envois = 0;

        Donation::where('recurring', true)->where('is_anonymous', false)->chunkById(100, function ($dons) use (&$envois) {
            foreach ($dons as $don) {
                $contact = (string) $don->donor_contact;

                if (filter_var($contact, FILTER_VALIDATE_EMAIL)) {
                    Mail::to($contact)->queue(new DonationReminder);
                    $envois++;
                }
            }
        });

        $this->info("Rappels de dons réguliers mis en file : {$envois}.");

        return self::SUCCESS;
    }
}
