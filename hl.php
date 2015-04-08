<?php

require_once 'vendor/autoload.php';

$cmd = new Commando\Command();

$cmd->setHelp('
Creates and uploaded pages to aws. You\'ll need an AWS json credentials file and data.

Expects datafile to be csv with the first line containing:
hotelid,hotelname

Then all other properties that will be needed by your template

eg:

hotelid,hotelname,checkin,rate,currency,roomtypeandrateplan,occupancy

To upload to S3 you must provide an AWS credentials file, a bucket name and also set the --upload flag

Example command:
time php hl.php --file ./data.csv  --name "test" --template "./templates/hl.html"
');

$cmd->option("file")
    ->require(true)
    ->describedAs("The .csv file to read the data from. First row is titles. \"hotelid\" and \"hotelname\" must come first.")
    ->must(function($filepath) {
        return file_exists($filepath);
    })
    ->map(function($filepath) {
        return realpath($filepath);
    });

$cmd->option("template")
    ->describedAs("Path to the TWIG template to use to generate the pages.")
    ->must(function($filepath) {
        return file_exists($filepath);
    })
    ->map(function($filepath) {
        return realpath($filepath);
    });

$cmd->option("awscreds")
    ->describedAs("Path to the AWS credentials file")
    ->must(function($filepath) {
        return file_exists($filepath);
    })
    ->map(function($filepath) {
        return realpath($filepath);
    });

$cmd->option("s3bucket")
    ->describedAs("S3 bucket to upload files to");

$cmd->option("upload")
    ->describeAs("Upload generated files to S3")
    ->needs([
        "s3bucket",
        "awscreds"
    ])
    ->boolean();

$cmd->option("name")
    ->require(true)
    ->describeAs("The directory name to save files into, not a path. Will covert to lower case")
    ->map(function($name) {
        return mb_strtolower(preg_replace('/[^\w+\d+]/i', "", $name));
    });

$cmd->option("count")
    ->describeAs("Only count the unique hotels, requires the data file to be specified.")
    ->boolean();


if ($cmd["count"]) {
    $hotel_counter = new \CreativeCherry\HotelLandingPages\Actions\HotelCountReport($cmd["file"]);
    $hotel_counter->createReport();
    exit;
}

$hotel_importer = new \CreativeCherry\HotelLandingPages\Actions\ImportHotels($cmd["file"]);
$hotels = $hotel_importer->import();

if ($cmd["template"]) {
    $page_creator = new \CreativeCherry\HotelLandingPages\Actions\CreatePages(
        $cmd["template"],
        $hotels,
        $cmd["name"],
        dirname(__FILE__) . DIRECTORY_SEPARATOR . "generatedpages"
    );
    $page_creator->createPagesLocally();
}

$report_creator = new \CreativeCherry\HotelLandingPages\Actions\CreateReport(
    $cmd["name"],
    $hotels,
    "http://" . $cmd["s3bucket"],
    dirname(__FILE__) . DIRECTORY_SEPARATOR . "generatedpages"
);
$report_creator->createReport();

if ($cmd["upload"]) {
    // Do the upload
    $page_uploader = new \CreativeCherry\HotelLandingPages\Actions\UploadPages(
        $cmd["awscreds"],
        $cmd["s3bucket"],
        $cmd["name"],
        dirname(__FILE__) . DIRECTORY_SEPARATOR . "generatedpages"
    );
    $page_uploader->uploadPages();
}
