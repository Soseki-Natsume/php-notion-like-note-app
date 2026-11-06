<?php

namespace Tests\Unit\Domain\Note;

use App\Domain\Note\Note;
use App\Domain\Note\NoteId;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

final class NoteTest extends TestCase
{
    public function test_create_returns_note_with_empty_title_and_content(): void
    {
        $note = Note::create();

        $this->assertSame('', $note->title());
        $this->assertSame('', $note->content());
    }

    public function test_change_title_updates_title(): void
    {
        $note = Note::create();

        $note->changeTitle('新しいタイトル');

        $this->assertSame('新しいタイトル', $note->title());
    }

    public function test_change_title_updates_updated_at(): void
    {
        $updatedAt = new DateTimeImmutable('2026-01-01 00:00:00');
        $note = Note::reconstruct(
            id: NoteId::generate(),
            title: '',
            content: '',
            createdAt: $updatedAt,
            updatedAt: $updatedAt,
        );

        $note->changeTitle('タイトル');

        $this->assertGreaterThan($updatedAt, $note->updatedAt());
    }

    public function test_change_content_does_not_change_created_at(): void
    {
        $updatedAt = new DateTimeImmutable('2026-01-01 00:00:00');
        $note = Note::reconstruct(
            id: NoteId::generate(),
            title: '',
            content: '',
            createdAt: $updatedAt,
            updatedAt: $updatedAt,
        );

        $note->changeContent('本文');

        $this->assertSame($updatedAt, $note->createdAt());
    }
}
