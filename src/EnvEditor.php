<?php

namespace Uvodo\Menv;

use Uvodo\Menv\Exceptions\EntryNotFoundAtIndexException;

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
        foreach ($this->entries as $entry) {
            if ($entry->getKey() == $name) {
                $entry->setValue($value);

                if (!is_null($comment)) {
                    $entry->setComment($comment);
                }
            }
        }

        return $this;
    }

    public function setByIndex(int $index, $value, ?string $comment = null): self
    {
        $entry = $this->entries[$index] ?? null;

        if (!$entry) {
            throw new EntryNotFoundAtIndexException;
        }

        $entry->setValue($value);

        if (!is_null($comment)) {
            $entry->setComment($comment);
        }

        return $this;
    }

    public function setComment(string $name, string $comment): self
    {
        foreach ($this->entries as $entry) {
            if ($entry->getKey() == $name) {
                $entry->setComment($comment);
            }
        }

        return $this;
    }

    public function setCommentByIndex(int $index, string $comment): self
    {
        $entry = $this->entries[$index] ?? null;

        if (!$entry) {
            throw new EntryNotFoundAtIndexException;
        }

        $entry->setComment($comment);

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
