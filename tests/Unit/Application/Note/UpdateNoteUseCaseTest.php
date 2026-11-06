<?php

namespace Tests\Unit\Application\Note;

use App\Application\Note\CreateNoteUseCase;
use App\Application\Note\UpdateNoteUseCase;
use App\Domain\Note\NoteId;
use App\Domain\Note\NoteNotFoundException;
use PHPUnit\Framework\TestCase;
use Tests\Support\InMemoryNoteRepository;

final class UpdateNoteUseCaseTest extends TestCase
{
    private InMemoryNoteRepository $repository;

    private UpdateNoteUseCase $useCase;

    protected function setUp(): void
    {
        $this->repository = new InMemoryNoteRepository;
        $this->useCase = new UpdateNoteUseCase($this->repository);
    }

    public function test_updates_title_and_content_of_existing_note(): void
    {
        $created = (new CreateNoteUseCase($this->repository))->handle();

        $dto = $this->useCase->handle(new NoteId($created->id), '更新後タイトル', '更新後本文');

        $this->assertSame('更新後タイトル', $dto->title);
        $this->assertSame('更新後本文', $dto->content);

        $saved = $this->repository->find(new NoteId($created->id));
        $this->assertNotNull($saved);
        $this->assertSame('更新後タイトル', $saved->title());
        $this->assertSame('更新後本文', $saved->content());
    }

    public function test_existing_note_updates_timestamp(): void
    {
        $created = (new CreateNoteUseCase($this->repository))->handle();
        $saved = $this->repository->find(new NoteId($created->id));
        $before = $saved->updatedAt();

        $this->useCase->handle(new NoteId($created->id), 'タイトル', '');

        $saved = $this->repository->find(new NoteId($created->id));
        $this->assertSame($saved->updatedAt()->getTimestamp(), time());
    }

    public function test_missing_note_throws_not_found_exception(): void
    {
        $this->expectException(NoteNotFoundException::class);

        $this->useCase->handle(NoteId::generate(), 'タイトル', '本文');
    }
}
