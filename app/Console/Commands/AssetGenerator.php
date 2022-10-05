<?php

namespace App\Console\Commands;

use App\Helper\PHPIco;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Contracts\Container\BindingResolutionException;
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
        $this->manifestJson = resource_path('/manifest/manifest.json');
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->generateFavicon();
        $this->generateManifest();
        $this->info('Manifest Generated');
        return 0;
    }

    /**
     * Generate favicon.ico
     * @return void 
     * @throws BindingResolutionException 
     */
    function generateFavicon()
    {
        $phpIco = new PHPIco($this->original, [[16, 16], [32, 32]]);
        $phpIco->save_ico(base_path('/public/favicon.ico'));
    }

    /**
     * Generate manifest json and icon
     * @return bool 
     * @throws FileNotFoundException 
     */
    function generateManifest()
    {
        $iconSizes = [72, 96, 144, 128, 144, 152, 192, 384, 512];
        $iconsManifestList = [];
        try {
            if (!File::exists($this->manifestJson)) throw new FileNotFoundException("File not found");
            $manifestSchema = json_decode(File::get($this->manifestJson));
            if (!is_dir(base_path("public/assets"))) {
                mkdir(base_path("public/assets"), 0777, true);
            }

            foreach ($iconSizes as $iconSize) {
                $iconsManifestList[] = (object) [
                    "src" => "/assets/icon-" . $iconSize . "x" . $iconSize . ".png",
                    "type" => "image/png",
                    "sizes" => $iconSize . "x" . $iconSize
                ];
                $this->resizeImage(
                    $iconSize,
                    base_path("public/assets/icon-" . $iconSize . "x" . $iconSize . ".png")
                );
            }
            $manifestSchema->icons = $iconsManifestList;
            File::put(base_path("public/manifest.json"), json_encode($manifestSchema));
            return true;
        } catch (Exception $e) {
            $this->error($e->getMessage());
            return false;
        }
    }

    /**
     * Resize image resolution
     * @param int $size Rectangle size array <width,height>
     * @param String $dest Destination file PNG
     * @return void 
     * @throws BindingResolutionException 
     */
    function resizeImage($size, $dest)
    {
        $imagine = new Imagine();
        $imagine->open($this->original)
            ->thumbnail(new Box($size, $size), ImageInterface::THUMBNAIL_OUTBOUND)
            ->save($dest);
    }
}
