<?php

namespace Tests\Support;

use App\Domain\Note\Note;
use App\Domain\Note\NoteId;
use App\Domain\Note\NoteRepository;

/**
 * ユースケースの単体テストで使うインメモリ実装。
 */
final class InMemoryNoteRepository implements NoteRepository
{
    /** @var array<string, Note> */
    private array $notes = [];

    /**
     * @return list<Note>
     */
    public function list(): array
    {
        $notes = array_values($this->notes);

        usort($notes, static fn (Note $a, Note $b): int => $b->updatedAt() <=> $a->updatedAt());

        return $notes;
    }

    public function find(NoteId $id): ?Note
    {
        return $this->notes[$id->value()] ?? null;
    }

    public function save(Note $note): void
    {
        $this->notes[$note->id()->value()] = $note;
    }

    public function delete(NoteId $id): void
    {
        unset($this->notes[$id->value()]);
    }
}
