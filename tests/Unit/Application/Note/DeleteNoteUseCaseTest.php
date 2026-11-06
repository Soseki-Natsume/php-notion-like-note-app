<?php

namespace Tests\Unit\Application\Note;

use App\Application\Note\CreateNoteUseCase;
use App\Application\Note\DeleteNoteUseCase;
use App\Domain\Note\NoteId;
use PHPUnit\Framework\TestCase;
use Tests\Support\InMemoryNoteRepository;

final class DeleteNoteUseCaseTest extends TestCase
{
    private InMemoryNoteRepository $repository;

    private DeleteNoteUseCase $useCase;

    protected function setUp(): void
    {
        $this->repository = new InMemoryNoteRepository;
        $this->useCase = new DeleteNoteUseCase($this->repository);
    }

    public function test_deletes_existing_note_and_returns_true(): void
    {
        $created = (new CreateNoteUseCase($this->repository))->handle();

        $result = $this->useCase->handle(new NoteId($created->id));

        $this->assertTrue($result);
        $this->assertNull($this->repository->find(new NoteId($created->id)));
    }

    public function test_returns_false_for_missing_note(): void
    {
        $result = $this->useCase->handle(NoteId::generate());

        $this->assertFalse($result);
    }
}
