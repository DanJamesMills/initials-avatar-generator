<?php

namespace DanJamesMills\InitialsAvatarGenerator;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\AbstractFont;
use Intervention\Image\AbstractShape;
use Intervention\Image\ImageManager;

class InitialsAvatarGenerator
{
    /**
     * Size of avatar width image in pixels.
     *
     * @var int
     */
    private $width;

    /**
     * Size of avatar height image in pixels.
     *
     * @var int
     */
    private $height;

    /**
     * Font colour of avatar initials.
     *
     * @var string
     */
    private $fontColour;

    /**
     * Font size of avatar initials.
     *
     * @var string
     */
    private $fontSize;

    /**
     * If initials should be uppercase.
     *
     * @var bool
     */
    private $uppercase;

    /**
     * If initials should be bold.
     *
     * @var bool
     */
    private $bold;

    /**
     * Background colour ranges that
     * can be used to generate avatar.
     *
     * @var array
     */
    private $colourRange;

    /**
     * Decide if the API should
     * return SVG or PNG.
     *
     * @var string
     */
    private $fileFormat;

    /**
     * Boolean specifying if the
     * returned avatar should be a circle.
     *
     * @var bool
     */
    private $rounded;

    /**
     * Size of avatar border in pixels.
     *
     * @var int
     */
    protected $borderSize;

    /**
     * Border colour.
     *
     * @var string
     */
    protected $borderColour;

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
     * A filename set by user
     * for saving avatar file.
     *
     * @var string
     */
    private $customFilename;

    /**
     * @var \Intervention\Image\Image
     */
    protected $image;

    /**
     * Font file and location.
     *
     * @var string
     */
    protected $font;

    protected $shape = 'square';

    public function __construct()
    {
        $this->resetClassOptionsBackToDefault();
    }

    protected function avatarSavePath(): string
    {
        return \Config::get('initials-avatar-generator.storage_path');
    }

    protected function getStorageDisk(): string
    {
        return \Config::get('initials-avatar-generator.storage_disk');
    }

    /**
     * Gets a random background colour
     * from colourRange array.
     */
    protected function getRandomBackgroundColour(): string
    {
        return $this->colourRange[array_rand($this->colourRange)];
    }

    /**
     * Save avatar image
     * to disk.
     */
    private function saveAvatarImageToDisk(): void
    {
        if (! $this->getFilename()) {
            $this->generatedFilename = $this->generateRandomFilename();
        }

        $filePath = $this->avatarSavePath().$this->getFilename();

        $imageContents = (string) $this->image->encode('jpg', 100);

        Storage::disk($this->getStorageDisk())->put($filePath, $imageContents);
    }

    private function getFilename()
    {
        if ($this->customFilename) {
            return 'IAG'.$this->customFilename.'.'.$this->fileFormat;
        }

        if ($this->generatedFilename) {
            return $this->generatedFilename;
        }
    }

    /**
     * Generates a random file name.
     */
    protected function generateRandomFilename(): string
    {
        return 'IAG'.sha1($this->name.time()).'.'.$this->fileFormat;
    }

    /**
     * Generate initials from a name.
     */
    private function makeInitials(): void
    {
        if (strlen($this->name) == 2) {
            $this->initials = $this->name;

            return;
        }

        $words = explode(' ', $this->name);

        if (count($words) >= 2) {
            $this->initials = substr($words[0], 0, 1).substr(end($words), 0, 1);

            return;
        }

        $this->makeInitialsFromSingleWord();
    }

    /**
     * Make initials from a word with no spaces.
     */
    protected function makeInitialsFromSingleWord(): void
    {
        preg_match_all('#([A-Z]+)#', $this->name, $capitals);

        if (count($capitals[1]) >= 2) {
            $this->initials = substr(implode('', $capitals[1]), 0, 2);
        }
        $this->initials = substr($this->name, 0, 2);
    }

    public function getInitials(): string
    {
        return ($this->uppercase) ? strtoupper($this->initials) : $this->initials;
    }

    /**
     * Background colour ranges that can be used to generate avatar.
     *
     *
     * @return $this
     */
    public function backgroundColourRanges(array $colourRange): self
    {
        $this->colourRange = $colourRange;

        return $this;
    }

    /**
     * Set the avatar/image width in pixels.
     *
     *
     * @return $this
     */
    public function width(int $width): self
    {
        $this->width = $width;

        return $this;
    }

    /**
     * Set the avatar/image height in pixels.
     *
     *
     * @return $this
     */
    public function height(int $height): self
    {
        $this->height = $height;

        return $this;
    }

    /**
     * Set the name used for generating initials.
     *
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
     *
     * @return $this
     */
    public function rounded(bool $rounded = true): self
    {
        if ($rounded) {
            $this->shape = 'circle';
        } else {
            $this->shape = 'square';
        }

        return $this;
    }

    /**
     * Set initials should be uppercase or not.
     *
     *
     * @return $this
     */
    public function uppercase(bool $uppercase = true): self
    {
        $this->uppercase = $uppercase;

        return $this;
    }

    /**
     * Set filename that will be used
     * to save avatar file as only pass
     * name not with file extension.
     *
     *
     * @return $this
     */
    public function filename(string $filename): self
    {
        if (! empty(pathinfo($filename, PATHINFO_EXTENSION))) {
            throw new \Exception('Filename should not contain any file extensions');
        }

        $this->customFilename = $filename;

        return $this;
    }

    private function getSupportedFileFormats(): array
    {
        return ['png', 'svg'];
    }

    /**
     * Set avatar file format.
     *
     *
     * @return $this
     */
    public function fileFormat(string $fileFormat): self
    {
        if (! in_array($fileFormat, $this->getSupportedFileFormats())) {
            throw new \Exception("File format not supported, accepted file formats are 'png' or 'svg'");
        }

        $this->fileFormat = $fileFormat;

        return $this;
    }

    /**
     * Reset class options
     * back to default.
     */
    private function resetClassOptionsBackToDefault(): void
    {
        $this->width(config('initials-avatar-generator.width'));

        $this->height(config('initials-avatar-generator.height'));

        $this->borderSize = config('initials-avatar-generator.border_size');

        $this->borderColour = config('initials-avatar-generator.border_colour');

        // $this->font = config('initials-avatar-generator.font');

        $this->font = __DIR__.'/../fonts/OpenSans-Bold.ttf';

        $this->fontColour = config('initials-avatar-generator.font_colour');

        $this->fontSize = config('initials-avatar-generator.font_size');

        $this->uppercase(config('initials-avatar-generator.uppercase'));

        $this->bold = config('initials-avatar-generator.bold');

        $this->backgroundColourRanges(config('initials-avatar-generator.colour_range'));

        $this->fileFormat(config('initials-avatar-generator.file_format'));

        $this->rounded(config('initials-avatar-generator.rounded'));
    }

    public function getGeneratedFilename(): string
    {
        return $this->generatedFilename;
    }

    public function generate()
    {
        $this->makeInitials();

        $this->buildAvatar();

        $this->saveAvatarImageToDisk();

        $this->resetClassOptionsBackToDefault();

        return $this->getFilename();
    }

    public function buildAvatar()
    {
        $x = $this->width / 2;
        $y = $this->height / 2;

        $manager = new ImageManager(['driver' => 'gd']);
        $this->image = $manager->canvas($this->width, $this->height);

        $this->createShape();

        $this->image->text(
            $this->getInitials(),
            $x,
            $y,
            function (AbstractFont $font) {
                $font->file($this->font);
                $font->size($this->fontSize);
                $font->color($this->fontColour);
                $font->align('center');
                $font->valign('middle');
            }
        );
    }

    protected function createShape()
    {
        $method = 'create'.ucfirst($this->shape).'Shape';
        if (method_exists($this, $method)) {
            return $this->$method();
        }

        throw new \InvalidArgumentException("Shape [$this->shape] currently not supported.");
    }

    protected function createCircleShape()
    {
        $circleDiameter = $this->width - $this->borderSize;
        $x = $this->width / 2;
        $y = $this->height / 2;

        $this->image->circle(
            $circleDiameter,
            $x,
            $y,
            function (AbstractShape $draw) {
                $draw->background($this->getRandomBackgroundColour());
                $draw->border($this->borderSize, $this->borderColour);
            }
        );
    }

    protected function createSquareShape()
    {
        $edge = (ceil($this->borderSize / 2));
        $x = $y = $edge;
        $width = $this->width - $edge;
        $height = $this->height - $edge;

        $this->image->rectangle(
            $x,
            $y,
            $width,
            $height,
            function (AbstractShape $draw) {
                $draw->background($this->getRandomBackgroundColour());
                $draw->border($this->borderSize, $this->borderColour);
            }
        );
    }
}
