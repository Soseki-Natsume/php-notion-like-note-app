<?php

namespace App\Application\Note;

use App\Domain\Note\Note;
use JsonSerializable;

/**
 * ユースケースが外部層へ返すノートの DTO。
 */
final class NoteDto implements JsonSerializable
{
    public function __construct(
        public readonly string $id,
        public readonly string $title,
        public readonly string $content,
        public readonly string $createdAt,
        public readonly string $updatedAt,
    ) {}

    public static function fromDomain(Note $note): self
    {
        return new self(
            id: $note->id()->value(),
            title: $note->title(),
            content: $note->content(),
            createdAt: $note->createdAt()->format(DATE_ATOM),
            updatedAt: $note->updatedAt()->format(DATE_ATOM),
        );
    }

    /**
     * @return array{id: string, title: string, content: string, created_at: string, updated_at: string}
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt,
        ];
    }

    /**
     * @return array{id: string, title: string, content: string, created_at: string, updated_at: string}
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
