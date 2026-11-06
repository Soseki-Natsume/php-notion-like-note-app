<?php

namespace Tests\Unit\Domain\Note;

use App\Domain\Note\NoteId;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class NoteIdTest extends TestCase
{
    public function test_valid_uuid_is_accepted(): void
    {
        $id = new NoteId('123e4567-e89b-12d3-a456-426614174000');

        $this->assertSame('123e4567-e89b-12d3-a456-426614174000', $id->value());
    }

    public function test_generate_produces_a_valid_uuid(): void
    {
        $id = NoteId::generate();

        $this->assertMatchesRegularExpression(
            '/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/',
            $id->value(),
        );
    }

    public function test_invalid_string_is_rejected(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new NoteId('not-a-uuid');
    }

    public function test_same_value_equals_returns_true(): void
    {
        $a = new NoteId('123e4567-e89b-12d3-a456-426614174000');
        $b = new NoteId('123e4567-e89b-12d3-a456-426614174000');

        $this->assertTrue($a->equals($b));
    }

    public function test_different_value_equals_returns_false(): void
    {
        $a = new NoteId('123e4567-e89b-12d3-a456-426614174000');
        $b = new NoteId('123e4567-e89b-12d3-a456-426614174001');

        $this->assertFalse($a->equals($b));
    }
}
