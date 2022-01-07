<?php

namespace Uvodo\Menv;

class EnvEditor
{
    /** @var Entry[] $entries */
    private array $entries = [];
    private string $path;

    public function __construct(string $path)
    {
        $this->path = $path;
        $this->parse();
    }

    public function set(string $name, $value, ?string $comment = null): self
    {
        $entries = [];
        foreach ($this->entries as $entry) {
            if ($entry->getKey() == $name) {
                $entry->setValue($value);

                if (!is_null($comment)) {
                    $entry->setComment($comment);
                }
            }

            $entries[] = $entry;
        }

        $this->entries = $entries;
        return $this;
    }

    public function save()
    {
        $output = [];
        foreach ($this->entries as $entry) {
            $output[] = $entry->getLine();
        }

        file_put_contents($this->path, implode("\n", $output));

        echo "<pre>";
        echo implode("\n", $output);
        echo "</pre>";
    }

    private function parse()
    {
        $handle = fopen($this->path, 'r');

        while (($line = fgets($handle)) !== false) {
            $this->entries[] = new Entry($line);
        }

        fclose($handle);
    }
}
