<?php

namespace App\Infrastructure\Note;

use App\Domain\Note\Note;
use App\Domain\Note\NoteId;
use App\Domain\Note\NoteRepository;
use Carbon\CarbonInterface;

/**
 * NoteRepository の Eloquent 実装。
 */
final class EloquentNoteRepository implements NoteRepository
{
    /**
     * @return list<Note>
     */
    public function list(): array
    {
        return NoteModel::query()
            ->orderByDesc('updated_at')
            ->orderByDesc('id')
            ->get()
            ->map(fn (NoteModel $model): Note => $this->toDomain($model))
            ->all();
    }

    public function find(NoteId $id): ?Note
    {
        /** @var NoteModel|null $model */
        $model = NoteModel::query()->find($id->value());

        return $model === null ? null : $this->toDomain($model);
    }

    public function save(Note $note): void
    {
        NoteModel::query()->updateOrCreate(
            ['id' => $note->id()->value()],
            [
                'title' => $note->title(),
                'content' => $note->content(),
                'created_at' => $note->createdAt(),
                'updated_at' => $note->updatedAt(),
            ],
        );
    }

    public function delete(NoteId $id): void
    {
        NoteModel::query()->whereKey($id->value())->delete();
    }

    private function toDomain(NoteModel $model): Note
    {
        return Note::reconstruct(
            id: new NoteId($model->id),
            title: $model->title,
            content: $model->content,
            createdAt: $this->toDateTimeImmutable($model->created_at),
            updatedAt: $this->toDateTimeImmutable($model->updated_at),
        );
    }

    private function toDateTimeImmutable(CarbonInterface $carbon): \DateTimeImmutable
    {
        return \DateTimeImmutable::createFromInterface($carbon->toDateTime());
    }
}
