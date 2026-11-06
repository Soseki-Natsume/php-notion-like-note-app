<?php

namespace App\Application\Note;

use App\Domain\Note\Note;
use App\Domain\Note\NoteRepository;

/**
 * 新規ノートを作成するユースケース。
 */
final class CreateNoteUseCase
{
    public function __construct(private readonly NoteRepository $repository) {}

    public function handle(): NoteDto
    {
        $note = Note::create();

        $this->repository->save($note);

        return NoteDto::fromDomain($note);
    }
}
