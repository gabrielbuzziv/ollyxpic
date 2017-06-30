<?php

namespace App\Http\Controllers;

use App\Helper\GifCreator;
use App\Helper\GifFrameExtractor;
use App\Items;
use App\NPC;
use App\WorldMap;
use GIFEndec\Decoder;
use GIFEndec\Events\FrameRenderedEvent;
use GIFEndec\IO\FileStream;
use GIFEndec\Renderer;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{

    /**
     * Load image.
     *
     * @param $filename
     * @return $this
     */
    public function load($filename)
    {
        if ($path = $this->fileExists('items', "{$filename}.gif")) {
            $file = File::get($path);
            $type = File::mimeType($path);

            return Response::make($file, 200)->header('Content-Type', $type);
        }
    }

    /**
     * Load blob images.
     *
     * @param $type
     * @param $id
     * @return mixed
     */
    public function loadImage($type, $id)
    {
        $image = $this->loadBlobImage($type, $id);

        return Response::make($image, 200)->header('Content-Type', 'image/gif');
    }
    
    /**
     * Split multiple frames gifs.
     */
    public function stackables()
    {
        $items = Items::where('stackable', true)->get();

        foreach ($items as $item) {
            $filename = str_slug($item->title);
            $path = storage_path("app/items/{$filename}.gif");

            if (GifFrameExtractor::isAnimatedGif($path)) {

                $file = new FileStream($path);
                $decoder = new Decoder($file);
                $renderer = new Renderer($decoder);

                $renderer->start(function (FrameRenderedEvent $event) use ($filename) {
                    $amount = [
                        0  => 1,
                        1  => 2,
                        2  => 3,
                        3  => 4,
                        4  => 5,
                        5  => 10,
                        6  => 25,
                        7  => 50
                    ];

                    if ($event->frameIndex > 7) {
                        return false;
                    }

                    imagegif($event->renderedFrame, storage_path("app/items/{$filename}-{$amount[$event->frameIndex]}.gif"));
                });
            }
        }
    }

    /**
     * Convert blob images.
     *
     * @param $type
     * @param $id
     * @return mixed
     */
    private function loadBlobImage($type, $id)
    {
        switch ($type) {
            case 'npc':
                return NPC::find($id)->image;
            case 'item':
                return Items::find($id)->image;
            case 'map':
                return WorldMap::where('z', $id)->first()->image;
        }
    }

    /**
     * Check if file exist, if not abort 404,
     * if exist just return the path of file.
     *
     * @param $directory
     * @param $file
     * @return string
     */
    private function fileExists($directory, $file)
    {
        $path = storage_path("app/{$directory}/{$file}");

        if (! File::exists($path)) abort(404);

        return $path;
    }
}
