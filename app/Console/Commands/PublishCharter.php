<?php

namespace App\Console\Commands;

use App\Models\Charter;
use Illuminate\Console\Command;

class PublishCharter extends Command
{
    protected $signature = 'charters:publish {type : listening ou provider} {version : ex. 1.1} {file : fichier HTML du texte}';

    protected $description = 'Publie une nouvelle version de charte (les utilisateurs devront la signer à leur prochain accès)';

    public function handle(): int
    {
        $type = $this->argument('type');

        if (! in_array($type, [Charter::LISTENING, Charter::PROVIDER], true)) {
            $this->error('Le type doit être « listening » ou « provider ».');

            return self::FAILURE;
        }

        $file = $this->argument('file');

        if (! is_file($file)) {
            $this->error("Fichier introuvable : {$file}");

            return self::FAILURE;
        }

        if (Charter::where('type', $type)->where('version', $this->argument('version'))->exists()) {
            $this->error('Cette version existe déjà : une version publiée ne se modifie pas.');

            return self::FAILURE;
        }

        $charter = Charter::publish($type, $this->argument('version'), file_get_contents($file));

        $this->info("Charte {$type} v{$charter->version} publiée.");

        return self::SUCCESS;
    }
}
