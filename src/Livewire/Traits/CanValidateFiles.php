<?php

namespace Jeddsaliba\FilamentMessages\Livewire\Traits;

trait CanValidateFiles
{
    protected $validAudioExtensions = [
        'm4a',  // For audio/m4a
        'wav',  // For audio/wav
        'mp3',  // For audio/mpeg (commonly associated with MP3 files)
        'ogg',  // For audio/ogg
        'aac',  // For audio/aac
        'flac', // For audio/flac
        'midi', // For audio/midi (alternative extension: .mid)
    ];

    protected $validDocumentExtensions = [
        'pdf',    // For application/pdf
        'doc',    // For application/msword
        'docx',   // For application/vnd.openxmlformats-officedocument.wordprocessingml.document
        'csv',    // For text/csv
        'txt',    // For text/plain
        'xls',    // For application/vnd.ms-excel
        'xlsx',   // For application/vnd.openxmlformats-officedocument.spreadsheetml.sheet
        'ppt',    // For application/vnd.ms-powerpoint
        'pptx',   // For application/vnd.openxmlformats-officedocument.presentationml.presentation
    ];

    protected $validImageExtensions = [
        'png',  // For image/png
        'jpeg', // For image/jpeg
        'jpg',  // For image/jpg
        'gif',  // For image/gif
    ];

    protected $validVideoExtensions = [
        'mp4',      // For video/mp4
        'avi',      // For video/avi
        'mov',      // For video/quicktime
        'webm',     // For video/webm
        'mkv',      // For video/x-matroska
        'flv',      // For video/x-flv
        'mpeg',     // For video/mpeg
        'mpg',      // For video/mpeg (alternative extension)
    ];

    /**
     * Validates if the given audio file path has a valid audio extension.
     *
     * @param string $imagePath The path to the audio file.
     * @return bool Returns true if the file extension is valid, false otherwise.
     */
    public function validateAudio(string $imagePath): bool
    {
        $extension = strtolower(pathinfo($imagePath, PATHINFO_EXTENSION));

        if (in_array($extension, $this->validAudioExtensions)) {
            return true;
        }

        return false;
    }

    /**
     * Validates if the given document file path has a valid document extension.
     *
     * @param string $documentPath The path to the document file.
     * @return bool Returns true if the file extension is valid, false otherwise.
     */
    public function validateDocument(string $documentPath): bool
    {
        $extension = strtolower(pathinfo($documentPath, PATHINFO_EXTENSION));

        if (in_array($extension, $this->validDocumentExtensions)) {
            return true;
        }

        return false;
    }

    /**
     * Validates if the given image file path has a valid image extension.
     *
     * @param string $imagePath The path to the image file.
     * @return bool Returns true if the file extension is valid, false otherwise.
     */
    public function validateImage(string $imagePath): bool
    {
        $extension = strtolower(pathinfo($imagePath, PATHINFO_EXTENSION));

        if (in_array($extension, $this->validImageExtensions)) {
            return true;
        }

        return false;
    }

    /**
     * Validates if the given video file path has a valid video extension.
     *
     * @param string $imagePath The path to the video file.
     * @return bool Returns true if the file extension is valid, false otherwise.
     */
    public function validateVideo(string $imagePath): bool
    {
        $extension = strtolower(pathinfo($imagePath, PATHINFO_EXTENSION));

        if (in_array($extension, $this->validVideoExtensions)) {
            return true;
        }

        return false;
    }
}
