<?php

namespace Tests\Unit\Application\Note;

use App\Application\Note\CreateNoteUseCase;
use App\Application\Note\ReadNoteUseCase;
use App\Domain\Note\NoteId;
use PHPUnit\Framework\TestCase;
use Tests\Support\InMemoryNoteRepository;

final class ReadNoteUseCaseTest extends TestCase
{
    private InMemoryNoteRepository $repository;

    private ReadNoteUseCase $useCase;

    protected function setUp(): void
    {
        $this->repository = new InMemoryNoteRepository;
        $this->useCase = new ReadNoteUseCase($this->repository);
    }

    public function test_returns_note_dto_for_existing_id(): void
    {
        $created = (new CreateNoteUseCase($this->repository))->handle();

        $dto = $this->useCase->handle(new NoteId($created->id));

        $this->assertNotNull($dto);
        $this->assertSame($created->id, $dto->id);
    }

    public function test_returns_null_for_missing_id(): void
    {
        $dto = $this->useCase->handle(NoteId::generate());

        $this->assertNull($dto);
    }
}
