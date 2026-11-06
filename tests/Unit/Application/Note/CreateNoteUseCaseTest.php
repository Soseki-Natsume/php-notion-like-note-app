<?php

namespace Tests\Unit\Application\Note;

use App\Application\Note\CreateNoteUseCase;
use App\Domain\Note\NoteId;
use PHPUnit\Framework\TestCase;
use Tests\Support\InMemoryNoteRepository;

final class CreateNoteUseCaseTest extends TestCase
{
    private InMemoryNoteRepository $repository;

    private CreateNoteUseCase $useCase;

    protected function setUp(): void
    {
        $this->repository = new InMemoryNoteRepository;
        $this->useCase = new CreateNoteUseCase($this->repository);
    }

    public function test_creates_empty_note_and_returns_it(): void
    {
        $dto = $this->useCase->handle();

        $this->assertSame('', $dto->title);
        $this->assertSame('', $dto->content);
        $this->assertNotSame('', $dto->id);
    }

    public function test_created_note_is_persisted(): void
    {
        $dto = $this->useCase->handle();

        $saved = $this->repository->find(new NoteId($dto->id));

        $this->assertNotNull($saved);
        $this->assertSame($dto->title, $saved->title());
        $this->assertSame($dto->content, $saved->content());
    }
}
