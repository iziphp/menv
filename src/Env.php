<?php

namespace Uvodo\Menv;

use Uvodo\Menv\Exceptions\EntryNotFoundAtIndexException;
use Uvodo\Menv\Exceptions\EntryNotFoundWithKeyException;
use Uvodo\Menv\Exceptions\FileIsNotWritableException;
use Uvodo\Menv\Exceptions\FileNotFoundException;
use Uvodo\Menv\Exceptions\InvalidEntryValueException;

/** @package Uvodo\Menv */
class Env
{
    /** @var Entry[] $entries */
    private array $entries = [];
    private string $path;

    /**
     * @param string $path 
     * @return void 
     * @throws FileNotFoundException 
     * @throws FileIsNotWritableException 
     */
    public function __construct(string $path)
    {
        $this->checkFile($path);
        $this->path = $path;
        $this->parse();
    }

    /**
     * Set the value (and optionally comment) for the entry found by key.
     * @param string $key 
     * @param mixed $value 
     * @param null|string $comment 
     * @return Env 
     * @throws EntryNotFoundWithKeyException 
     * @throws InvalidEntryValueException 
     */
    public function set(string $key, $value, ?string $comment = null): self
    {
        $entry = $this->getEntryByKey($key);
        $entry->setValue($value);

        if (!is_null($comment)) {
            $entry->setComment($comment);
        }

        return $this;
    }

    /**
     * Set the value (and optionally comment) for the entry found by index.
     * Indices are the line number (start from 0).
     * @param int $index 
     * @param mixed $value 
     * @param null|string $comment 
     * @return Env 
     * @throws EntryNotFoundAtIndexException 
     * @throws InvalidEntryValueException 
     */
    public function setByIndex(int $index, $value, ?string $comment = null): self
    {
        $entry = $this->getEntryByIndex($index);
        $entry->setValue($value);

        if (!is_null($comment)) {
            $entry->setComment($comment);
        }

        return $this;
    }

    /**
     * Set the comment for the entry found by key.
     * @param string $name 
     * @param string $comment 
     * @return Env 
     * @throws EntryNotFoundWithKeyException 
     */
    public function setComment(string $name, string $comment): self
    {
        $entry = $this->getEntryByKey($name);
        $entry->setComment($comment);

        return $this;
    }

    /**
     * Set the comment for the entry found by index. 
     * Indices are the line number (start from 0).
     * @param int $index 
     * @param string $comment 
     * @return Env 
     * @throws EntryNotFoundAtIndexException 
     */
    public function setCommentByIndex(int $index, string $comment): self
    {
        $entry = $this->getEntryByIndex($index);
        $entry->setComment($comment);

        return $this;
    }

    /**
     * Save entries back to the file.
     * @return void 
     */
    public function save()
    {
        $output = [];
        foreach ($this->entries as $entry) {
            $output[] = $entry->getLine();
        }

        file_put_contents($this->path, implode("\n", $output));
    }

    /**
     * Validates the file path on construct.
     * @param string $path 
     * @return void 
     * @throws FileNotFoundException 
     * @throws FileIsNotWritableException 
     */
    private function checkFile(string $path)
    {
        if (!file_exists($path)) {
            throw new FileNotFoundException($path);
        }

        if (!is_writable($path)) {
            throw new FileIsNotWritableException($path);
        }
    }

    /**
     * Read the file line by line and parse each entry line.
     * @return void 
     */
    private function parse()
    {
        $handle = fopen($this->path, 'r');

        while (($line = fgets($handle)) !== false) {
            $this->entries[] = new Entry($line);
        }

        fclose($handle);
    }

    /**
     * Find entry by key.
     * @param string $key 
     * @return Entry 
     * @throws EntryNotFoundWithKeyException 
     */
    private function getEntryByKey(string $key): Entry
    {
        foreach ($this->entries as $entry) {
            if ($entry->getKey() == $key) {
                return $entry;
            }
        }

        throw new EntryNotFoundWithKeyException($key);
    }

    /**
     * Find entry by index. Indices are the line number (start from 0).
     * @param int $index 
     * @return Entry 
     * @throws EntryNotFoundAtIndexException 
     */
    private function getEntryByIndex(int $index): Entry
    {
        if (isset($this->entries[$index])) {
            return $this->entries[$index];
        }

        throw new EntryNotFoundAtIndexException($index);
    }
}
