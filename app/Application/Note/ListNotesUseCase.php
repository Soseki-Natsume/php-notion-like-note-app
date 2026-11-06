<?php

namespace App\Application\Note;

use App\Domain\Note\Note;
use App\Domain\Note\NoteRepository;

/**
 * ノートの一覧を取得するユースケース。
 */
final class ListNotesUseCase
{
    public function __construct(private readonly NoteRepository $repository) {}

    /**
     * @return list<NoteDto>
     */
    public function handle(): array
    {
        return array_map(
            static fn (Note $note): NoteDto => NoteDto::fromDomain($note),
            $this->repository->list(),
        );
    }
}
