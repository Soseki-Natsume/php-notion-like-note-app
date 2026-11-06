<?php

namespace App\Application\Note;

use App\Domain\Note\NoteId;
use App\Domain\Note\NoteNotFoundException;
use App\Domain\Note\NoteRepository;

/**
 * ノートを更新するユースケース。
 */
final class UpdateNoteUseCase
{
    public function __construct(private readonly NoteRepository $repository) {}

    public function handle(NoteId $id, string $title, string $content): NoteDto
    {
        $note = $this->repository->find($id);

        if ($note === null) {
            throw new NoteNotFoundException($id);
        }

        $note->changeTitle($title);
        $note->changeContent($content);

        $this->repository->save($note);

        return NoteDto::fromDomain($note);
    }
}
