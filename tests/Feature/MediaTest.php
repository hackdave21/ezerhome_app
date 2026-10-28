<?php

use App\Livewire\ComitePhotos;
use App\Livewire\MediaLibrary;
use App\Models\MediaAsset;
use App\Models\MediaUsage;
use App\Models\Setting;
use App\Models\User;
use App\Services\Media\SvgSanitizer;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->withoutVite();
    $this->seed(RolesAndPermissionsSeeder::class);
    Storage::fake(MediaAsset::PUBLIC_DISK);
    Storage::fake(MediaAsset::PRIVATE_DISK);
});

/** Compte interne avec rôle et 2FA confirmée (obligatoire pour comité et admin). */
function interne(string $role): User
{
    $user = User::factory()->create();
    $user->assignRole($role);

    $user->forceFill(['two_factor_secret' => encrypt('SECRET'), 'two_factor_confirmed_at' => now()])->save();

    return $user;
}

/** JPEG contenant un faux segment EXIF avec une « position GPS » identifiable. */
function jpegAvecExif(int $largeur = 2400, int $hauteur = 1600): UploadedFile
{
    $img = imagecreatetruecolor($largeur, $hauteur);
    imagefill($img, 0, 0, imagecolorallocate($img, 200, 30, 30));
    ob_start();
    imagejpeg($img);
    $jpeg = ob_get_clean();

    $exif = "Exif\0\0GPSLatitude=6.1725N;GPSLongitude=1.2314E";
    $segment = "\xFF\xE1".pack('n', strlen($exif) + 2).$exif;

    return UploadedFile::fake()->createWithContent('photo.jpg', substr($jpeg, 0, 2).$segment.substr($jpeg, 2));
}

function pdfFictif(string $nom = 'statuts.pdf'): UploadedFile
{
    return UploadedFile::fake()->createWithContent($nom, "%PDF-1.4\n1 0 obj<<>>endobj\ntrailer<<>>\n%%EOF");
}

// -------------------------------------------------------- traitement

it('convertit en WebP, redimensionne, crée une miniature et supprime l\'EXIF', function () {
    $source = jpegAvecExif();
    expect(file_get_contents($source->getRealPath()))->toContain('GPSLatitude');

    Livewire::actingAs(interne('admin'))->test(MediaLibrary::class)->set('uploads', [$source]);

    $asset = MediaAsset::first();

    expect($asset->status)->toBe('ready')
        ->and($asset->mime)->toBe('image/webp')
        ->and($asset->width)->toBe(1600)
        ->and($asset->height)->toBe(1067)
        ->and($asset->path)->toEndWith('.webp')
        ->and($asset->thumb_path)->toEndWith('-miniature.webp')
        ->and($asset->incoming_path)->toBeNull();

    $disque = Storage::disk($asset->disk);
    expect($disque->exists($asset->path))->toBeTrue()->and($disque->exists($asset->thumb_path))->toBeTrue();

    // Aucune trace de la géolocalisation, ni dans l'image ni dans la miniature.
    expect($disque->get($asset->path))->not->toContain('GPSLatitude')
        ->and($disque->get($asset->thumb_path))->not->toContain('GPSLatitude');

    [$largeurMiniature] = getimagesizefromstring($disque->get($asset->thumb_path));
    expect($largeurMiniature)->toBe(400);

    // Le fichier d'origine (avec EXIF) n'est pas conservé.
    expect(Storage::disk(MediaAsset::PRIVATE_DISK)->allFiles('incoming'))->toBe([]);
});

it('accepte plusieurs fichiers en une fois', function () {
    Livewire::actingAs(interne('admin'))->test(MediaLibrary::class)
        ->set('uploads', [jpegAvecExif(800, 600), pdfFictif()]);

    expect(MediaAsset::count())->toBe(2)->and(MediaAsset::where('mime', 'application/pdf')->exists())->toBeTrue();
});

it('assainit les SVG : scripts, gestionnaires d\'événements et liens externes', function () {
    $sale = '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" onload="alert(1)" width="10" height="10">'
        .'<script>alert(1)</script><rect width="10" height="10" onclick="x()" fill="red"/>'
        .'<a xlink:href="javascript:alert(1)"><circle r="3" style="fill:url(http://evil.test/x)"/></a>'
        .'<foreignObject><iframe src="http://evil.test"></iframe></foreignObject>'
        .'<use href="http://evil.test/x.svg#a"/><image href="data:image/png;base64,iVBORw0KGgo="/></svg>';

    $propre = app(SvgSanitizer::class)->sanitize($sale);

    expect($propre)->toContain('<rect')->and($propre)->toContain('fill="red"')->and($propre)->toContain('data:image/png')
        ->and($propre)->not->toContain('script')->not->toContain('onload')->not->toContain('onclick')
        ->not->toContain('javascript:')->not->toContain('evil.test')->not->toContain('foreignObject')->not->toContain('iframe');
});

it('refuse un SVG invalide et stocke un SVG assaini', function () {
    expect(app(SvgSanitizer::class)->sanitize('<html><body>pas un svg</body></html>'))->toBeNull();

    $svg = UploadedFile::fake()->createWithContent('logo.svg', '<svg xmlns="http://www.w3.org/2000/svg"><script>alert(1)</script><rect width="5" height="5"/></svg>');

    Livewire::actingAs(interne('admin'))->test(MediaLibrary::class)->set('uploads', [$svg]);

    $asset = MediaAsset::first();
    expect($asset->mime)->toBe('image/svg+xml')
        ->and(Storage::disk($asset->disk)->get($asset->path))->not->toContain('script')->toContain('<rect');
});

// ------------------------------------------------ contrôle du type réel

it('contrôle le type MIME réel, pas l\'extension', function () {
    $faux = UploadedFile::fake()->createWithContent('photo.jpg', '<?php echo "pirate"; ?>');
    $exe = UploadedFile::fake()->createWithContent('doc.pdf', "MZ\x90\x00 programme windows");

    $composant = Livewire::actingAs(interne('admin'))->test(MediaLibrary::class)->set('uploads', [$faux, $exe]);

    expect(MediaAsset::count())->toBe(0)->and($composant->get('errorsUpload'))->toHaveCount(2);
});

it('respecte la taille maximale paramétrée', function () {
    Setting::put('media.max_mb', '1');
    $gros = UploadedFile::fake()->createWithContent('gros.pdf', "%PDF-1.4\n".str_repeat('a', 1024 * 1024 + 10));

    $composant = Livewire::actingAs(interne('admin'))->test(MediaLibrary::class)->set('uploads', [$gros]);

    expect(MediaAsset::count())->toBe(0)->and($composant->get('errorsUpload')[0])->toContain('1 Mo');
});

// ------------------------------------------------- accès aux fichiers

it('ne sert jamais un fichier privé par URL directe', function () {
    $admin = interne('admin');
    Livewire::actingAs($admin)->test(MediaLibrary::class)->set('uploads', [jpegAvecExif(800, 600)]);
    $asset = MediaAsset::first();

    expect($asset->is_public)->toBeFalse()->and($asset->disk)->toBe(MediaAsset::PRIVATE_DISK);

    // Le disque privé est hors du dossier public : aucun chemin web direct.
    expect(config('filesystems.disks.private_media.root'))->not->toStartWith(public_path())
        ->and(config('filesystems.disks.public_media.root'))->toStartWith(storage_path('app/public'));

    auth()->logout();
    $this->get($asset->url())->assertNotFound();                                   // visiteur
    $this->actingAs(interne('consultant'))->get($asset->url())->assertForbidden(); // autre rôle
    $this->actingAs(interne('comite'))->get($asset->url())->assertForbidden();     // comité : hors catégorie assistance
    $this->actingAs($admin)->get($asset->url())->assertOk();                       // admin
});

it('sert les fichiers publiables à tous, avec des en-têtes de sécurité', function () {
    $admin = interne('admin');
    $composant = Livewire::actingAs($admin)->test(MediaLibrary::class)->set('uploads', [jpegAvecExif(800, 600)]);

    $asset = MediaAsset::first();
    $composant->call('select', $asset->id)->set('alt', 'Une image rouge')->set('isPublic', true)->call('save');

    $asset->refresh();
    expect($asset->disk)->toBe(MediaAsset::PUBLIC_DISK)->and(Storage::disk(MediaAsset::PUBLIC_DISK)->exists($asset->path))->toBeTrue()
        ->and(Storage::disk(MediaAsset::PRIVATE_DISK)->exists($asset->path))->toBeFalse();

    auth()->logout();
    $this->get($asset->url())->assertOk()
        ->assertHeader('X-Content-Type-Options', 'nosniff')
        ->assertHeader('Content-Type', 'image/webp');
    $this->get($asset->url(thumb: true))->assertOk();
});

it('rend de nouveau privé un fichier dépublié', function () {
    $admin = interne('admin');
    $composant = Livewire::actingAs($admin)->test(MediaLibrary::class)->set('uploads', [jpegAvecExif(800, 600)]);
    $asset = MediaAsset::first();
    $composant->call('select', $asset->id)->set('alt', 'Texte')->set('isPublic', true)->call('save')
        ->set('isPublic', false)->call('save');

    expect($asset->fresh()->disk)->toBe(MediaAsset::PRIVATE_DISK);
    auth()->logout();
    $this->get($asset->url())->assertNotFound();
});

// ------------------------------------------------------- suppression

it('refuse de supprimer un fichier utilisé et liste les pages concernées', function () {
    $composant = Livewire::actingAs(interne('admin'))->test(MediaLibrary::class)->set('uploads', [jpegAvecExif(800, 600)]);
    $asset = MediaAsset::first();
    MediaUsage::create(['media_asset_id' => $asset->id, 'label' => 'Accueil · bloc « Assistance »', 'url' => url('/')]);
    MediaUsage::create(['media_asset_id' => $asset->id, 'label' => 'À quoi servent les dons · section 1', 'url' => url('/a-quoi-servent-les-dons')]);

    $composant->call('select', $asset->id)->call('delete')
        ->assertSet('deleteBlocked', true)
        ->assertSee('Suppression impossible')
        ->assertSee('Ce fichier est utilisé sur 2 pages')
        ->assertSee('Accueil · bloc « Assistance »')
        ->assertSee('À quoi servent les dons · section 1');

    expect(MediaAsset::count())->toBe(1)->and(Storage::disk($asset->disk)->exists($asset->path))->toBeTrue();
});

it('supprime un fichier inutilisé avec ses fichiers physiques', function () {
    $composant = Livewire::actingAs(interne('admin'))->test(MediaLibrary::class)->set('uploads', [jpegAvecExif(800, 600)]);
    $asset = MediaAsset::first();
    $chemin = $asset->path;
    $disque = $asset->disk;

    $composant->call('select', $asset->id)->call('delete')->assertSet('selectedId', null);

    expect(MediaAsset::count())->toBe(0)->and(Storage::disk($disque)->exists($chemin))->toBeFalse();
});

// -------------------------------------------------------- remplacement

it('remplace le fichier en conservant l\'identifiant et l\'URL', function () {
    $composant = Livewire::actingAs(interne('admin'))->test(MediaLibrary::class)->set('uploads', [jpegAvecExif(800, 600)]);
    $asset = MediaAsset::first();
    $ancienChemin = $asset->path;
    $url = $asset->url();

    $composant->call('select', $asset->id)->set('replacement', jpegAvecExif(1000, 500))->call('replaceFile');

    $asset->refresh();
    expect($asset->id)->toBe(MediaAsset::first()->id)->and($asset->url())->toBe($url)
        ->and($asset->path)->not->toBe($ancienChemin)
        ->and($asset->width)->toBe(1000)
        ->and(Storage::disk($asset->disk)->exists($ancienChemin))->toBeFalse()
        ->and(Storage::disk($asset->disk)->exists($asset->path))->toBeTrue()
        ->and(MediaAsset::count())->toBe(1);
});

// ------------------------------------------------- métadonnées et accord

it('exige le texte alternatif pour une image et édite les métadonnées', function () {
    $composant = Livewire::actingAs(interne('admin'))->test(MediaLibrary::class)->set('uploads', [jpegAvecExif(800, 600)]);
    $asset = MediaAsset::first();

    $composant->call('select', $asset->id)->set('alt', '')->call('save')->assertHasErrors('alt');

    $composant->set('title', 'Distribution de vivres')->set('alt', 'Bénévoles remettant des sacs')->set('caption', 'Légende')
        ->set('credit', 'Ezer Home')->set('editCategory', 'organigramme')->call('save')->assertHasNoErrors();

    expect($asset->fresh())->title->toBe('Distribution de vivres')->category->toBe('organigramme')->credit->toBe('Ezer Home');
});

it('exige l\'accord de publication pour rendre une photo d\'assistance publiable', function () {
    $composant = Livewire::actingAs(interne('admin'))->test(MediaLibrary::class)->set('uploads', [jpegAvecExif(800, 600)]);
    $asset = MediaAsset::first();

    $composant->call('select', $asset->id)->set('alt', 'Photo')->set('editCategory', 'assistance')->set('isPublic', true)
        ->call('save')->assertHasErrors('isPublic');

    expect($asset->fresh()->is_public)->toBeFalse();

    $composant->set('consent', true)->call('save')->assertHasNoErrors();

    expect($asset->fresh())->is_public->toBeTrue()->consent_confirmed->toBeTrue();
});

it('filtre et recherche', function () {
    $composant = Livewire::actingAs(interne('admin'))->test(MediaLibrary::class)
        ->set('uploads', [pdfFictif('statuts-2025.pdf'), pdfFictif('rapport-activite.pdf')]);
    MediaAsset::where('original_name', 'rapport-activite.pdf')->update(['category' => 'assistance']);

    $composant->set('search', 'statuts')->assertSee('statuts 2025')->assertDontSee('rapport activite');
    $composant->set('search', '')->call('setCategory', 'assistance')->assertSee('rapport activite')->assertDontSee('statuts 2025');
});

it('archive un fichier et le masque du public', function () {
    $composant = Livewire::actingAs(interne('admin'))->test(MediaLibrary::class)->set('uploads', [jpegAvecExif(800, 600)]);
    $asset = MediaAsset::first();
    $composant->call('select', $asset->id)->set('alt', 'x')->set('isPublic', true)->call('save');

    $composant->call('toggleArchive');

    expect($asset->fresh()->isArchived())->toBeTrue();
    auth()->logout();
    $this->get($asset->url())->assertNotFound();
});

// ------------------------------------------------------------- droits

it('réserve la médiathèque à l\'Admin et les photos d\'assistance au Comité', function () {
    $this->get('/admin/mediatheque')->assertRedirect('/login');

    $this->actingAs(interne('consultant'))->get('/admin/mediatheque')->assertForbidden();
    $this->get('/comite/photos')->assertForbidden();

    $this->actingAs(interne('therapeute'))->get('/admin/mediatheque')->assertForbidden();

    $this->actingAs(interne('comite'))->get('/admin/mediatheque')->assertForbidden();
    $this->get('/comite/photos')->assertOk()->assertSee('Photos des actions');

    $this->actingAs(interne('admin'))->get('/admin/mediatheque')->assertOk()->assertSee('Médiathèque');
});

it('limite le comité à la catégorie assistance dans le composant', function () {
    MediaAsset::create(['original_name' => 'a.pdf', 'title' => 'Doc assistance', 'category' => 'assistance', 'status' => 'ready', 'path' => 'x.pdf']);
    MediaAsset::create(['original_name' => 'b.pdf', 'title' => 'Doc partenaire', 'category' => 'logo_partenaire', 'status' => 'ready', 'path' => 'y.pdf']);

    // Même appelé directement, le composant ne montre au comité que la catégorie assistance.
    Livewire::actingAs(interne('comite'))->test(MediaLibrary::class)->assertSee('Doc assistance')->assertDontSee('Doc partenaire');

    expect(interne('comite')->can('update', MediaAsset::where('category', 'logo_partenaire')->first()))->toBeFalse();
});

it('empêche le comité d\'enregistrer hors catégorie assistance', function () {
    $asset = MediaAsset::create(['original_name' => 'b.pdf', 'title' => 'Doc partenaire', 'category' => 'logo_partenaire', 'status' => 'ready', 'path' => 'y.pdf']);

    Livewire::actingAs(interne('comite'))->test(MediaLibrary::class)->call('select', $asset->id)->assertForbidden();
});

// ------------------------------------------------------ photos d'assistance

it('exige l\'accord de publication pour enregistrer des photos d\'assistance', function () {
    Livewire::actingAs(interne('comite'))->test(ComitePhotos::class)
        ->set('photos', [jpegAvecExif(800, 600)])->set('caption', 'Remise de fournitures')
        ->call('save')->assertHasErrors('consent');

    expect(MediaAsset::count())->toBe(0);
});

it('enregistre des photos d\'assistance publiables avec accord, sans nom ni contact', function () {
    Livewire::actingAs(interne('comite'))->test(ComitePhotos::class)
        ->set('photos', [jpegAvecExif(800, 600), jpegAvecExif(900, 600)])
        ->set('caption', 'Remise de fournitures scolaires')->set('actionDate', '2026-09-12')->set('linkedRequest', 'ASS-2026-0042')
        ->set('consent', true)->call('save')->assertHasNoErrors();

    $photos = MediaAsset::all();

    expect($photos)->toHaveCount(2)
        ->and($photos->every(fn ($p) => $p->category === 'assistance' && $p->consent_confirmed && $p->is_public && $p->status === 'ready'))->toBeTrue()
        ->and($photos->first()->alt)->toBe('Remise de fournitures scolaires')
        ->and($photos->first()->disk)->toBe(MediaAsset::PUBLIC_DISK)
        ->and($photos->first()->action_date->toDateString())->toBe('2026-09-12');

    auth()->logout();
    $this->get($photos->first()->url())->assertOk();
});

it('n\'accepte que JPG et PNG côté comité', function () {
    $composant = Livewire::actingAs(interne('comite'))->test(ComitePhotos::class)->set('photos', [pdfFictif('doc.pdf')]);

    expect($composant->get('errorsUpload'))->toHaveCount(1)->and($composant->get('photos'))->toBe([]);
});

it('ne publie pas les photos d\'assistance sans accord de publication', function () {
    $sansAccord = MediaAsset::create([
        'original_name' => 'a.jpg', 'title' => 'Photo', 'category' => 'assistance', 'status' => 'ready',
        'path' => 'a.webp', 'mime' => 'image/webp', 'is_public' => false, 'consent_confirmed' => false,
    ]);

    $this->get($sansAccord->url())->assertNotFound();
});

it('affiche la médiathèque vide et les totaux', function () {
    Livewire::actingAs(interne('admin'))->test(MediaLibrary::class)
        ->assertSee('La médiathèque est vide.')->assertSee('0 fichier');
});
