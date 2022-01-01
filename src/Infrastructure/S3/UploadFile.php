<?php

namespace Guess\Infrastructure\S3;

use Aws\S3\S3Client;
use Guess\Application\Services\FileUploaderInterface;
use Symfony\Component\String\Slugger\AsciiSlugger;

class UploadFile implements FileUploaderInterface
{
    private string $s3Object = '';
    private string $bucketName = '';

    public function __construct(
        private S3Client $s3Client
    ) {}

    public function upload(string $bucketName, string $objectName, string $imageUrl): void
    {
        $slugger = new AsciiSlugger();
        $this->s3Object = strtolower($slugger->slug($objectName) . '.png');
        $this->bucketName = $bucketName;

        $this->s3Client->putObject(
            [
                'Bucket' => $this->bucketName,
                'Key' => $this->s3Object,
                'Body' => file_get_contents($imageUrl),
                'ACL' => 'public-read',
            ]
        );
    }

    public function getImageUrl(): string
    {
        return "https://".$this->bucketName.".s3.eu-central-1.amazonaws.com/".$this->s3Object;
    }
}
