<?php

namespace Tests\Unit\Application\Note;

use App\Application\Note\ListNotesUseCase;
use App\Domain\Note\Note;
use App\Domain\Note\NoteId;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Tests\Support\InMemoryNoteRepository;

final class ListNotesUseCaseTest extends TestCase
{
    private InMemoryNoteRepository $repository;

    private ListNotesUseCase $useCase;

    protected function setUp(): void
    {
        $this->repository = new InMemoryNoteRepository;
        $this->useCase = new ListNotesUseCase($this->repository);
    }

    public function test_returns_empty_list_when_no_notes_exist(): void
    {
        $this->assertSame([], $this->useCase->handle());
    }

    public function test_returns_notes_in_descending_updated_at_order(): void
    {
        $this->repository->save($this->makeNote('old', '2026-01-01 00:00:00'));
        $this->repository->save($this->makeNote('new', '2026-02-01 00:00:00'));

        $dtos = $this->useCase->handle();

        $this->assertCount(2, $dtos);
        $this->assertSame('new', $dtos[0]->title);
        $this->assertSame('old', $dtos[1]->title);
    }

    private function makeNote(string $title, string $updatedAt): Note
    {
        return Note::reconstruct(
            id: NoteId::generate(),
            title: $title,
            content: '',
            createdAt: new DateTimeImmutable($updatedAt),
            updatedAt: new DateTimeImmutable($updatedAt),
        );
    }
}
