<?php

namespace App\Infrastructure\Note;

use Database\Factories\Infrastructure\Note\NoteModelFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * notes テーブルへのアクセスを担う Eloquent モデル。
 *
 * ドメイン層からは直接参照せず、EloquentNoteRepository 経由でのみ扱うこと。
 */
final class NoteModel extends Model
{
    /** @use HasFactory<NoteModelFactory> */
    use HasFactory;

    protected $table = 'notes';

    protected $primaryKey = 'id';

    protected $keyType = 'string';

    public $incrementing = false;

    /** @var list<string> */
    protected $fillable = [
        'id',
        'title',
        'content',
        'created_at',
        'updated_at',
    ];
}
