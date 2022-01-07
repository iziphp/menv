<?php

namespace Uvodo\Menv;

use Uvodo\Menv\Exceptions\InvalidEntryException;
use Uvodo\Menv\Exceptions\InvalidEntryValueException;

class Entry
{
    private string $line;
    private ?string $key = null;
    private ?string $value = null;
    private ?string $comment = null;
    private bool $isUpdated = false;

    public function __construct(string $line)
    {
        $this->line = trim($line);

        if ($this->line) {
            $this->parse($this->line);
        }
    }

    private function parse(string $line)
    {
        $pattern = '/^([a-zA-Z_]+[a-zA-Z0-9_]*)(\s*)=(.*)$/';

        if (substr($line, 0, 1) == '#') {
            $this->comment = substr($line, 1);
        } else if (preg_match($pattern, $line, $matches)) {
            $this->key = $matches[1];
            $right = $matches[3];

            $pattern = '/(\'[^\']*\'|"[^"\\\\"]*")(*SKIP)(*F)|#+/';
            $parts = preg_split($pattern, trim($right), 2);

            $this->value = trim($parts[0]) === '' ? null : trim($parts[0]);

            if (isset($parts[1])) {
                $this->comment = trim($parts[1]);
            }
        } else {
            throw new InvalidEntryException;
        }
    }

    public function getLine(): string
    {
        if (!$this->isUpdated) {
            return $this->getOriginalLine();
        }

        $out = '';
        if ($this->key) {
            $out .= $this->key . '=' . (is_null($this->value) ? '' : $this->value);
        }

        if ($this->comment) {
            $out .= ' # ' . $this->comment;
        }

        return $out;
    }

    public function getOriginalLine(): string
    {
        return $this->line;
    }

    public function __toString()
    {
        return $this->getLine();
    }

    public function getKey(): ?string
    {
        return $this->key;
    }

    public function setValue($value)
    {
        if (is_string($value)) {
            $value = trim($value);

            if ($value === 'true') {
                $value = '1';
            } elseif ($value === 'false') {
                $value = '0';
            } elseif (is_numeric($value)) {
                $this->value = (string) $value;
            } else {
                if (preg_match('/[^a-z0-9]/i', $value, $matches)) {
                    $pattern = '/(?<!\\\\)(?:\\\\\\\\)*(")/';
                    $value = preg_replace($pattern, '\"', $value);

                    $value = '"' . $value . '"';
                }

                $this->value = $value;
            }
        } elseif (is_bool($value)) {
            $this->value = $value === true ? '1' : '0';
        } elseif (is_numeric($value)) {
            $this->value = (string) $value;
        } else {
            throw new InvalidEntryValueException;
        }

        $this->isUpdated = true;
    }

    public function setComment(string $comment)
    {
        $this->comment = $comment;
    }
}
