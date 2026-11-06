<?php

namespace App\Domain\Note;

use InvalidArgumentException;
use Ramsey\Uuid\Uuid;

/**
 * ノート識別子の値オブジェクト。
 */
final class NoteId
{
    /**
     * @param  non-empty-string  $value  UUID形式の文字列
     */
    public function __construct(private readonly string $value)
    {
        if (! Uuid::isValid($value)) {
            throw new InvalidArgumentException("NoteId must be a valid UUID: {$value}");
        }
    }

    public static function generate(): self
    {
        return new self(Uuid::uuid4()->toString());
    }

    /**
     * @return non-empty-string
     */
    public function value(): string
    {
        return $this->value;
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
