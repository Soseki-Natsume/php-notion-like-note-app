<?php

namespace App\Domain\Note;

/**
 * ノート永続化の抽象。
 *
 * 実装はインフラストラクチャ層に置き、本層からはこのインターフェースのみを参照する。
 */
interface NoteRepository
{
    /**
     * ノートを updated_at の降順で取得する。
     *
     * @return list<Note>
     */
    public function list(): array;

    public function find(NoteId $id): ?Note;

    public function save(Note $note): void;

    /**
     * 対象が存在しない場合は何もしない(冪等)。
     */
    public function delete(NoteId $id): void;
}
