<?php

namespace DanJamesMills\InitialsAvatarGenerator;

class InitialsAvatarGenerator
{
    /**
     * Api base request url.
     *
     * @var string
     */
    private $apiBaseUrl = 'https://eu.ui-avatars.com/api/';

    /**
     * Size of avatar image in pixels.
     *
     * @var int
     */
    private $size = 500;

    /**
     * Font colour of avatar initials.
     *
     * @var string
     */
    private $fontColour = 'ffffff';

    /**
     * If initials should be uppercase.
     *
     * @var boolean
     */
    private $uppercase = true;

    /**
     * If initials should be bold.
     *
     * @var boolean
     */
    private $bold = false;

    /**
     * Background colour ranges that
     * can be used to generate avatar.
     *
     * @var array
     */
    private $colourRange = [
        'ff5622',
        '8057ff',
        '4d88ff',
        'ff4169',
        '673ab7',
        '03a9f4',
        '26c5da',
        '00ac7c',
        'c0ca33',
        'ffb201',
    ];

    /**
     * Decide if the API should
     * return SVG or PNG.
     *
     * @var string
     */
    private $fileFormat = 'png';

    /**
     * Boolean specifying if the
     * returned avatar should be a circle.
     *
     * @var bool
     */
    private $rounded = false;

    /**
     * Name used for generating initials.
     *
     * @param string
     */
    private $name;

    /**
     * Avatar file as as string.
     *
     * @var string
     */
    private $avatarFileString;

    /**
     * File path of where to save
     * avatar file to.
     *
     * @var string
     */
    private $avatarSavePath;

    /**
     * Initials made from
     * name passed.
     *
     * @var string
     */
    private $initials;

    /**
     * A generated file name used
     * for saving avatar file.
     *
     * @var string
     */
    private $generatedFilename;

    /**
     * Returns a url to api endpoint
     * with populated parameters.
     *
     * @return string
     */
    private function getAvatarGeneratorUrl(): string
    {
        $parameters = [
            'name' => $this->initials,
            'size' => $this->size,
            'background' => $this->getRandomBackgroundColour(),
            'color' => $this->fontColour,
            'rounded' => $this->rounded,
            'uppercase' => $this->uppercase,
            'bold' => $this->bold,
            'format' => $this->fileFormat
        ];

        return $this->apiBaseUrl . '?' . http_build_query($parameters);
    }

    protected function avatarSavePath(): string
    {
        return storage_path('app/public/avatars/');
    }

    /**
     * Gets a random background colour
     * from colourRange array.
     *
     * @return string
     */
    protected function getRandomBackgroundColour(): string
    {
        return $this->colourRange[array_rand($this->colourRange)];
    }

    /**
     * Save avatar file
     * to disk.
     *
     * @return void
     */
    private function saveAvatarFileToDisk(): void
    {
        $this->generatedFilename = $this->generateRandomFilename();

        file_put_contents($this->avatarSavePath().$this->generatedFilename, $this->avatarFileString);
    }

    /**
     * Generates a random file name.
     *
     * @return string
     */
    protected function generateRandomFilename(): string
    {
        return 'IAG' . sha1($this->name . time()). '.' . $this->fileFormat;
    }

    /**
     * Fletches avatar file from
     * api endpoint.
     *
     * @return void
     */
    private function getAvatarFromApi(): void
    {
        $this->avatarFileString = file_get_contents($this->getAvatarGeneratorUrl());
    }

    /**
     * Generate initials from a name.
     *
     * @return void
     */
    private function makeInitials(): void
    {
        if (strlen($this->name) == 2) {
            $this->initials = $this->name;
            return;
        }

        $words = explode(' ', $this->name);

        if (count($words) >= 2) {
            $this->initials = substr($words[0], 0, 1) . substr(end($words), 0, 1);
            return;
        }

        $this->makeInitialsFromSingleWord();
    }

    /**
     * Make initials from a word with no spaces.
     *
     * @return void
     */
    protected function makeInitialsFromSingleWord(): void
    {
        preg_match_all('#([A-Z]+)#', $this->name, $capitals);

        if (count($capitals[1]) >= 2) {
            $this->initials = substr(implode('', $capitals[1]), 0, 2);
        }
        $this->initials = substr($this->name, 0, 2);
    }

    /**
     * Set the avatar/image size in pixels.
     *
     * @param int $size
     *
     * @return $this
     */
    public function size(int $size): self
    {
        $this->size = $size;

        return $this;
    }

    /**
     * Set the name used for generating initials.
     *
     * @param string $nameOrInitials
     *
     * @return $this
     */
    public function name(string $nameOrInitials): self
    {
        $this->name = $nameOrInitials;

        return $this;
    }

    /**
     * Set avatar to be a circle.
     *
     * @param boolean $rounded
     *
     * @return $this
     */
    public function rounded(bool $rounded = true): self
    {
        $this->rounded = $rounded;

        return $this;
    }

    /**
     * Set initials should be uppercase or not.
     *
     * @param boolean $uppercase
     *
     * @return $this
     */
    public function uppercase(bool $uppercase = true): self
    {
        $this->uppercase = $uppercase;

        return $this;
    }

    private function getSupportedFileFormats(): array
    {
        return ['png', 'svg'];
    }

    /**
     * Set avatar file format.
     *
     * @param string $fileFormat
     *
     * @return $this
     */
    public function fileFormat(string $fileFormat): self
    {
        if (!in_array($fileFormat, $this->getSupportedFileFormats())) {
            throw new \Exception("File format not supported, accepted file formats are 'png' or 'svg'");
        }
        
        $this->fileFormat = $fileFormat;

        return $this;
    }
    
    /**
     * Reset class options
     * back to default.
     *
     * @return void
     */
    private function resetClassOptionsBackToDefault(): void
    {
        $this->size = 500;
        $this->fontColour = 'ffffff';
        $this->uppercase = true;
        $this->bold = false;
        $this->fileFormat = 'png';
        $this->rounded = false;
    }

    public function getGeneratedFilename(): string
    {
        return $this->generatedFilename;
    }

    public function generate()
    {
        $this->makeInitials();

        $this->getAvatarFromApi();

        $this->saveAvatarFileToDisk();

        $this->resetClassOptionsBackToDefault();

        return $this->getGeneratedFilename();
    }
}
