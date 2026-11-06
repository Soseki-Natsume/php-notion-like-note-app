<?php

namespace App\Application\Note;

use App\Domain\Note\NoteId;
use App\Domain\Note\NoteRepository;

/**
 * ノートを削除するユースケース。
 */
final class DeleteNoteUseCase
{
    public function __construct(private readonly NoteRepository $repository) {}

    public function handle(NoteId $id): bool
    {
        if ($this->repository->find($id) === null) {
            return false;
        }

        $this->repository->delete($id);

        return true;
    }
}
