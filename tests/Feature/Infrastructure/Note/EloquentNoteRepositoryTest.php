<?php

namespace Tests\Feature\Infrastructure\Note;

use App\Domain\Note\Note;
use App\Domain\Note\NoteId;
use App\Infrastructure\Note\EloquentNoteRepository;
use App\Infrastructure\Note\NoteModel;
use DateTimeImmutable;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

final class EloquentNoteRepositoryTest extends TestCase
{
    use LazilyRefreshDatabase;

    private EloquentNoteRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = new EloquentNoteRepository;
    }

    public function test_list_returns_empty_array_when_no_notes_exist(): void
    {
        $this->assertSame([], $this->repository->list());
    }

    public function test_find_returns_null_for_note_that_does_not_exist(): void
    {
        $this->assertNull($this->repository->find(NoteId::generate()));
    }

    public function test_save_persists_a_new_note(): void
    {
        $note = Note::create(title: 'タイトル', content: '本文');

        $this->repository->save($note);

        $found = $this->repository->find($note->id());

        $this->assertNotNull($found);
        $this->assertSame('タイトル', $found->title());
        $this->assertSame('本文', $found->content());
        $this->assertTrue($note->id()->equals($found->id()));
    }

    public function test_save_updates_an_existing_note(): void
    {
        $note = Note::create(title: 'タイトル', content: '本文');
        $this->repository->save($note);

        $note->changeTitle('更新後タイトル');
        $this->repository->save($note);

        $this->assertSame(1, NoteModel::query()->count());
        $found = $this->repository->find($note->id());
        $this->assertNotNull($found);
        $this->assertSame('更新後タイトル', $found->title());
    }

    public function test_list_returns_notes_in_descending_updated_at_order(): void
    {
        $note1 = Note::reconstruct(
            id: NoteId::generate(),
            title: 'old',
            content: '',
            createdAt: new DateTimeImmutable('2026-01-01 00:00:00'),
            updatedAt: new DateTimeImmutable('2026-01-01 00:00:00'),
        );
        $note2 = Note::reconstruct(
            id: NoteId::generate(),
            title: 'new',
            content: '',
            createdAt: new DateTimeImmutable('2026-02-01 00:00:00'),
            updatedAt: new DateTimeImmutable('2026-02-01 00:00:00'),
        );
        $this->repository->save($note1);
        $this->repository->save($note2);

        $this->assertTrue($note2->id()->equals($this->repository->list()[0]->id()));

        $note1->changeTitle('updated');
        $this->repository->save($note1);

        $this->assertTrue($note1->id()->equals($this->repository->list()[0]->id()));
    }

    public function test_delete_removes_an_existing_note(): void
    {
        $note = Note::create();
        $this->repository->save($note);

        $this->repository->delete($note->id());

        $this->assertNull($this->repository->find($note->id()));
    }
}
