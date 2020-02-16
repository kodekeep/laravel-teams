<?php

declare(strict_types=1);

/*
 * This file is part of Laravel Teams.
 *
 * (c) KodeKeep <hello@kodekeep.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace KodeKeep\Teams\Tests\Unit\Actions;

use KodeKeep\Teams\Actions\AcceptTeamInvitationAction;
use KodeKeep\Teams\Models\TeamInvitation;
use KodeKeep\Teams\Tests\TestCase;

/**
 * @covers \KodeKeep\Teams\Actions\AcceptTeamInvitationAction
 */
class AcceptTeamInvitationActionTest extends TestCase
{
    /** @test */
    public function throws_when_a_different_user_attempts_to_accept_an_invitation(): void
    {
        $this->expectExceptionMessage('The user is not authorized to claim the given invitation.');

        $user       = $this->user();
        $team       = $this->team();
        $invitation = factory(TeamInvitation::class)->create([
            'team_id' => $team->id,
            'user_id' => $user->id,
        ]);

        $this->actingAs($team->owner);

        (new AcceptTeamInvitationAction($invitation))->execute();
    }

    /** @test */
    public function can_accept_the_team_invitation(): void
    {
        $user       = $this->user();
        $team       = $this->team();
        $invitation = factory(TeamInvitation::class)->create([
            'team_id' => $team->id,
            'user_id' => $user->id,
        ]);

        $this->actingAs($user);

        $this->assertDatabaseHas('team_invitations', ['id'=> $invitation->id]);
        $this->assertFalse($user->onTeam($team));

        (new AcceptTeamInvitationAction($invitation))->execute();

        $this->assertDatabaseMissing('team_invitations', ['id'=> $invitation->id]);
        $this->assertTrue($user->fresh()->onTeam($team));
    }
}
