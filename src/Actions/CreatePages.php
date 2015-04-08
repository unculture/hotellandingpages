<?php

namespace CreativeCherry\HotelLandingPages\Actions;


use CreativeCherry\HotelLandingPages\Model\HotelCollection;
use Twig_Loader_Filesystem;
use Twig_Environment;
use Twig_TemplateInterface;

class CreatePages {

    /**
     * @var Twig_TemplateInterface The Twig template
     */
    private $template;

    /**
     * @var HotelCollection The hotels
     */
    private $hotels;

    /**
     * @var string A name to use as a directory name for the pages
     */
    private $name;

    /**
     * @var string The directory in which to create output directories
     */
    private $rootdirectory;

    /**
     * @param string $template The path to the template file
     * @param HotelCollection $hotels
     * @param string $name A name to use as a directory name for the pages
     */
    public function __construct($template, HotelCollection $hotels, $name, $rootdirectory)
    {
        $this->template = $this->bootstrapTwig($template);
        $this->hotels = $hotels;
        $this->name = $name;
        $this->rootdirectory = $rootdirectory;
    }

    /**
     * @param $template
     * @return Twig_TemplateInterface
     */
    private function bootstrapTwig($template)
    {
        $realpath = realpath($template);
        $dirname = dirname($realpath);
        $basename = basename($realpath);

        $twig_loader = new Twig_Loader_Filesystem($dirname);
        $twig = new \Twig_Environment($twig_loader);
        return $twig->loadTemplate($basename);
    }

    /**
     * Create the pages on the local filesystem
     * @return void
     */
    public function createPagesLocally()
    {
        $this->ensureDirectoriesExist();
        foreach ($this->hotels as $hotel) {
            $result = $this->template->render([
                "hotel" => $hotel,
                "rooms" => $hotel->getRooms()
            ]);
            file_put_contents($this->rootdirectory . DIRECTORY_SEPARATOR .  $this->name . DIRECTORY_SEPARATOR . $hotel->getUniqueCode() . ".html", $result);
        }
    }

    /**
     * Make sure the appropriate directories exist
     * @return void
     */
    private function ensureDirectoriesExist()
    {
        if (!is_dir($this->rootdirectory . DIRECTORY_SEPARATOR .  $this->name)) {
            mkdir($this->rootdirectory . DIRECTORY_SEPARATOR .  $this->name, 0744, true);
        }
    }

}