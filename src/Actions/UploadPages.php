<?php

namespace CreativeCherry\HotelLandingPages\Actions;


use Aws\Common\Aws;
use Aws\S3\S3Client;

class UploadPages
{

    /**
     * @var string path to aws credentials file
     */
    private $awscredfile;

    /**
     * @var string the name of the bucket to upload files to
     */
    private $s3bucketname;

    /**
     * @var string the name associated with this run. Used to generate directories
     */
    private $name;

    /**
     * @var string the path to the directory in which to find the run directory containing pages
     */
    private $rootdirectory;

    public function __construct($awscredfile, $s3bucketname, $name, $rootdirectory)
    {

        $this->awscredfile = $awscredfile;
        $this->s3bucketname = $s3bucketname;
        $this->name = $name;
        $this->rootdirectory = $rootdirectory;
    }

    /**
     * Upload the generate pages to S3
     * @return void
     */
    public function uploadPages()
    {
        $s3client = $this->createS3Client();

        $files = scandir($this->rootdirectory . DIRECTORY_SEPARATOR . $this->name);

        foreach ($files as $file) {
            if (preg_match('/.*\.html$/', $file)) {
                $this->uploadFile($s3client, $file);
            }
        }
    }

    /**
     * Bootstrap the S3 Client
     * @return S3Client S3 Client
     */
    private function createS3Client()
    {
        $aws = Aws::factory($this->awscredfile);
        $s3client = $aws->get("s3");
        return $s3client;
    }

    /**
     * @param $s3client S3Client S3 client
     * @param string $file
     */
    private function uploadFile($s3client, $file)
    {
        try {
            $s3client->putObject(array(
                'Bucket' => $this->s3bucketname,
                'Key' => $this->name . "/" . basename($file),
                'Body' => fopen($this->rootdirectory . DIRECTORY_SEPARATOR . $this->name . DIRECTORY_SEPARATOR . $file, "r"),
                'ACL' => 'public-read',
                'ContentType' => 'text/html'
            ));
        } catch (\Exception $e) {
            echo "There was an error uploading the file. " . $file . "\n";
            echo "Message \n";
            echo $e->getMessage();
            echo "Code \n";
            echo $e->getCode();
            echo "File \n";
            echo $e->getFile();
            echo "Line \n";
            echo $e->getLine();
        }
    }

} 