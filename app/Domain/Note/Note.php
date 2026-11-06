<?php

namespace App\Domain\Note;

use DateTimeImmutable;

/**
 * ノートエンティティ。
 *
 * ドメイン層はフレームワークに依存しないこと。
 */
final class Note
{
    private function __construct(
        private readonly NoteId $id,
        private string $title,
        private string $content,
        private readonly DateTimeImmutable $createdAt,
        private DateTimeImmutable $updatedAt,
    ) {}

    public static function create(string $title = '', string $content = ''): self
    {
        return self::reconstruct(
            id: NoteId::generate(),
            title: $title,
            content: $content,
            createdAt: new DateTimeImmutable,
            updatedAt: new DateTimeImmutable,
        );
    }

    public static function reconstruct(
        NoteId $id,
        string $title,
        string $content,
        DateTimeImmutable $createdAt,
        DateTimeImmutable $updatedAt,
    ): self {
        return new self($id, $title, $content, $createdAt, $updatedAt);
    }

    public function changeTitle(string $title): void
    {
        $this->title = $title;
        $this->touch();
    }

    public function changeContent(string $content): void
    {
        $this->content = $content;
        $this->touch();
    }

    public function id(): NoteId
    {
        return $this->id;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function content(): string
    {
        return $this->content;
    }

    public function createdAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function updatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    private function touch(): void
    {
        $this->updatedAt = new DateTimeImmutable;
    }
}
