<?php

namespace Botble\Base\Supports;

use Botble\Base\Events\FinishedSeederEvent;
use Botble\Base\Events\SeederPrepared;
use Botble\Base\Models\MetaBox as MetaBoxModel;
use Botble\Media\Facades\RvMedia;
use Botble\Media\Models\MediaFile;
use Botble\Media\Models\MediaFolder;
use Botble\Setting\Facades\Setting;
use Faker\Generator;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Database\Seeder;
use Illuminate\Support\Composer;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;
use Throwable;

class BaseSeeder extends Seeder
{
    protected Generator $faker;

    public function uploadFiles(string $folder, string|null $basePath = null): array
    {
        $folderPath = $basePath ?: database_path('seeders/files/' . $folder);

        if (! File::isDirectory($folderPath)) {
            throw new FileNotFoundException('Folder not found: ' . $folderPath);
        }

        $storage = Storage::disk('public');

        if ($storage->exists($folder)) {
            $storage->deleteDirectory($folder);
        }

        MediaFile::query()->where('url', 'LIKE', $folder . '/%')->forceDelete();
        MediaFolder::query()->where('name', $folder)->forceDelete();

        $files = [];

        foreach (File::allFiles($folderPath) as $file) {
            $files[] = RvMedia::uploadFromPath($file, 0, $folder);
        }

        return $files;
    }

    protected function filePath(string $path, string|null $basePath = null): string
    {
        $storage = Storage::disk('public');

        if ($storage->exists($path)) {
            return $path;
        }

        $filePath = ($basePath ?: database_path('seeders/files/' . $path));

        if (! File::exists($filePath)) {
            throw new FileNotFoundException('File not found: ' . $filePath);
        }

        RvMedia::uploadFromPath($filePath, 0, File::dirname($path));

        return $path;
    }

    public function prepareRun(): void
    {
        $this->faker = $this->fake();

        Setting::newQuery()->truncate();

        Setting::forgetAll();

        Setting::forceSet('media_random_hash', md5((string)time()));

        Setting::set('api_enabled', 0);

        Setting::save();

        MetaBoxModel::query()->truncate();

        SeederPrepared::dispatch();
    }

    protected function random(int $from, int $to, array $exceptions = []): int
    {
        sort($exceptions); // lets us use break; in the foreach reliably
        $number = rand($from, $to - count($exceptions)); // or mt_rand()

        foreach ($exceptions as $exception) {
            if ($number >= $exception) {
                $number++; // make up for the gap
            } else { /*if ($number < $exception)*/
                break;
            }
        }

        return $number;
    }

    protected function finished(): void
    {
        FinishedSeederEvent::dispatch();
    }

    protected function fake(): Generator
    {
        if (isset($this->faker)) {
            return $this->faker;
        }

        if (! class_exists(\Faker\Factory::class)) {
            $this->command->warn('It requires <info>fakerphp/faker</info> to run seeder. Need to run <info>composer install</info> to install it first.');

            try {
                $composer = new Composer($this->command->getLaravel()['files']);

                $process = new Process(array_merge($composer->findComposer(), ['install']));

                $process->start();

                $process->wait(function ($type, $buffer) {
                    $this->command->line($buffer);
                });

                $this->command->warn('Please re-run <info>php artisan db:seed</info> command.');
            } catch (Throwable) {
            }

            exit(1);
        }

        $this->faker = fake();

        return $this->faker;
    }
}