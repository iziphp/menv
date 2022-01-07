<?php

namespace Uvodo\Menv;

use Uvodo\Menv\Exceptions\EntryNotFoundAtIndexException;
use Uvodo\Menv\Exceptions\EntryNotFoundWithKeyException;

class Env
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
        $entry = $this->getEntryByKey($name);
        $entry->setValue($value);

        if (!is_null($comment)) {
            $entry->setComment($comment);
        }

        return $this;
    }

    public function setByIndex(int $index, $value, ?string $comment = null): self
    {
        $entry = $this->getEntryByIndex($index);
        $entry->setValue($value);

        if (!is_null($comment)) {
            $entry->setComment($comment);
        }

        return $this;
    }

    public function setComment(string $name, string $comment): self
    {
        $entry = $this->getEntryByKey($name);
        $entry->setComment($comment);

        return $this;
    }

    public function setCommentByIndex(int $index, string $comment): self
    {
        $entry = $this->getEntryByIndex($index);
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
    }

    private function parse()
    {
        $handle = fopen($this->path, 'r');

        while (($line = fgets($handle)) !== false) {
            $this->entries[] = new Entry($line);
        }

        fclose($handle);
    }

    private function getEntryByKey(string $key): Entry
    {
        foreach ($this->entries as $entry) {
            if ($entry->getKey() == $key) {
                return $entry;
            }
        }

        throw new EntryNotFoundWithKeyException($key);
    }

    private function getEntryByIndex(int $index): Entry
    {
        if (isset($this->entries[$index])) {
            return $this->entries[$index];
        }

        throw new EntryNotFoundAtIndexException($index);
    }
}
