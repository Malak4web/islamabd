<?php

namespace App\Console\Commands;

use App\Models\Service;
use Illuminate\Console\Command;

/**
 * Repoints service records that still reference a third-party image host at the
 * matching photograph bundled under `public/images/services`.
 *
 * ServiceSeeder already writes the local paths, but it opens with
 * `Service::truncate()` — running it to correct a URL would destroy every
 * service the studio has edited since. This does the one thing that is
 * actually needed, is safe to run repeatedly, and leaves anything the studio
 * uploaded through the dashboard alone.
 */
class LocaliseRemoteImages extends Command
{
    protected $signature = 'media:localise {--dry-run : Report what would change without writing}';

    protected $description = 'Repoint service images hosted off-site at the bundled local photography';

    /**
     * English title fragment => local basename. Matching is on the title rather
     * than the id so a re-seeded or hand-edited table still resolves.
     */
    private const LINES = [
        'administrative' => 'administrative',
        'commercial'     => 'commercial',
        'residential'    => 'residential',
        'exterior'       => 'exterior',
        'hospitality'    => 'hospitality',
        'landscape'      => 'landscape',
        'retail'         => 'retail',
        'industrial'     => 'industrial',
    ];

    public function handle(): int
    {
        $dryRun  = (bool) $this->option('dry-run');
        $changed = 0;
        $skipped = [];

        foreach (Service::all() as $service) {
            $image = (string) $service->image;

            // Anything already local — including a dashboard upload — is left alone.
            if ($image !== '' && ! preg_match('#^https?://#i', $image)) {
                continue;
            }

            $key = $this->lineFor($service->title_en ?? '');

            if ($key === null) {
                $skipped[] = $service->title_en ?? "#{$service->id}";
                continue;
            }

            $target = "/images/services/{$key}-1080.jpg";

            if (! file_exists(public_path(ltrim($target, '/')))) {
                $skipped[] = ($service->title_en ?? "#{$service->id}")." (no file at {$target})";
                continue;
            }

            $this->line(sprintf('  %-22s %s', $service->title_en, $target));

            if (! $dryRun) {
                $service->image = $target;
                $service->save();
            }

            $changed++;
        }

        foreach ($skipped as $name) {
            $this->warn("  skipped: {$name}");
        }

        $this->info($dryRun
            ? "{$changed} service image(s) would be repointed."
            : "{$changed} service image(s) repointed.");

        return self::SUCCESS;
    }

    private function lineFor(string $title): ?string
    {
        $haystack = mb_strtolower($title);

        foreach (self::LINES as $needle => $key) {
            if (str_contains($haystack, $needle)) {
                return $key;
            }
        }

        return null;
    }
}
