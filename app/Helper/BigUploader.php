<?php
namespace App\Helper;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;
use Pion\Laravel\ChunkUpload\Exceptions\UploadMissingFileException;

class UploadFile
{
    public string $fileIndex;
    public string $outputFolder;
    public string $outputFile;

    public function __construct(string $fileIndex, string $outputFolder)
    {
        $this->fileIndex = $fileIndex;
        $this->outputFolder = $outputFolder;
    }
}

class BigUploader
{
    /** @var UploadFile */
    private UploadFile $uploadFile;
    private string $disk;
    /** @var FileReceiver */
    private FileReceiver $receiver;

    /**
     * Class constructor.
     * 
     * @param Request $request Request
     * @param UploadFile $uploadFile Array of UploadFile information
     * @param string $disk Disk name in filesystem
     * @return void 
     */
    public function __construct(Request $request, UploadFile $uploadFile, string $disk)
    {
        $this->uploadFile = $uploadFile;
        $this->disk = $disk;
        $this->receiver = new FileReceiver($uploadFile->fileIndex, $request, HandlerFactory::classFromRequest($request));;
    }

    public function upload()
    {
        if (!$this->receiver->isUploaded()) {
            // file not uploaded
            throw new UploadMissingFileException();
        }
        $fileReceived = $this->receiver->receive();
        if ($fileReceived->isFinished()) { // file uploading is complete / all chunks are uploaded
            $fileContent = $fileReceived->getFile(); // get file
            $extension = $fileContent->getClientOriginalExtension();
            $fileName = md5(time()) . '.' . $extension; // a unique file name

            $diskStorage = Storage::disk($this->disk);
            $path = $diskStorage->put($this->uploadFile->outputFolder, $fileContent, $fileName);

            // delete chunked file
            unlink($fileContent->getPathname());
            return [
                'path' => asset('storage/' . $path),
                'filename' => $fileName
            ];
        }

        // otherwise return percentage information
        $handler = $fileReceived->handler();
        return [
            'done' => $handler->getPercentageDone(),
            'status' => true
        ];
    }
}
