<?php

namespace App\Console\Commands;

use App\Helper\PHPIco;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Facades\File;
use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Imagine\Image\ImageInterface;

class AssetGenerator extends Command
{
    private $original;
    private $manifestJson;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'manifest:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate manifest json, icon, and images from base logo';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->original = resource_path('/manifest/original.png');
        $this->manifestJson = resource_path('manifest/generate.json');
        $this->manifest = resource_path('/json/manifest.json');
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Preparing data');
        $this->info('Generating image');
        $this->generateManifest();
        $this->generateIcon();
        $this->info('Manifest Generated');
        return 0;
    }

    function generateOriginal()
    {
        $image = new Imagick();
        $image->readImageBlob(file_get_contents('image.svg'));
        $image->setImageFormat("png24");
        $image->resizeImage(1024, 768, imagick::FILTER_LANCZOS, 1);
        $image->writeImage('image.png');
    }

    function generateIcon()
    {
        $phpIco = new PHPIco($this->original, [[16, 16], [32, 32]]);
        $phpIco->save_ico(resource_path('/images/favicon.ico'));
    }

    function generateManifest()
    {
        try {
            if (!File::exists($this->manifestJson)) throw new FileNotFoundException("File not found");
            $manifestSchema = json_decode(File::get($this->manifestJson));
            $manifestData = [];
            foreach ($manifestSchema as $manifest) {
                switch ($manifest->group) {
                    case 'Android':
                        foreach ($manifest->size as $index => $size) {
                            array_push($manifestData, [
                                'src' => '/' . $manifest->slug . '-' . $size . 'x' . $size . '.png',
                                'sizes' => $size . 'x' . $size,
                                'type' => 'image/png',
                                'density' => $manifest->density[$index]
                            ]);

                            $this->resizeImage(
                                $this->original,
                                $size,
                                $manifest->slug . '-' . $size . 'x' . $size
                            );
                        }
                        break;
                    case 'favicon':
                        foreach ($manifest->size as $size) {
                            $this->resizeImage(
                                $this->original,
                                $size,
                                'favicon'
                            );
                        }
                        break;
                    default:
                        foreach ($manifest->size as $size) {
                            $this->resizeImage(
                                $this->original,
                                $size,
                                $manifest->slug . '-' . $size . 'x' . $size
                            );
                        }

                        if (isset($manifest->custom)) {
                            foreach ($manifest->custom as $custom) {
                                $this->resizeImage(
                                    $this->original,
                                    $custom->size,
                                    $manifest->slug  . ($custom->name !== -1 ? '-' . $custom->name : '')
                                );
                            }
                        }
                        break;
                }
            }
            File::put($this->manifest, json_encode([
                "name" => config('app.name'),
                "icons" => $manifestData
            ]));
            return true;
        } catch (Exception $e) {
            $this->error($e->getMessage());
            return false;
        }
    }

    function resizeImage($source, $size, $output)
    {
        $output = '/images/favicon/' . $output . '.png';
        $imagine = new Imagine();
        $imagine->open($source)
            ->thumbnail(new Box($size, $size), ImageInterface::THUMBNAIL_OUTBOUND)
            ->save(resource_path($output));
    }
}
