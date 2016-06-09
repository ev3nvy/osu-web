<?php

/**
 *    Copyright 2016 ppy Pty. Ltd.
 *
 *    This file is part of osu!web. osu!web is distributed with the hope of
 *    attracting more community contributions to the core ecosystem of osu!.
 *
 *    osu!web is free software: you can redistribute it and/or modify
 *    it under the terms of the Affero GNU General Public License version 3
 *    as published by the Free Software Foundation.
 *
 *    osu!web is distributed WITHOUT ANY WARRANTY; without even the implied
 *    warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *    See the GNU Affero General Public License for more details.
 *
 *    You should have received a copy of the GNU Affero General Public License
 *    along with osu!web.  If not, see <http://www.gnu.org/licenses/>.
 */
namespace App\Models\Forum;

use Illuminate\Database\Eloquent\Model;

class PollOption extends Model
{
    protected $table = 'phpbb_poll_options';

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }

    public static function summary($topic, $user)
    {
        $summary = [
            'options' => [],
            'total' => 0,
            'user_votes' => 0,
        ];

        if ($topic->hasPoll()) {
            $userVotes = [];

            if ($user !== null) {
                $userVotes = model_pluck($topic->pollVotes()->where('vote_user_id', $user->getKey()), 'poll_option_id');
            }

            foreach ($topic->pollOptions as $poll) {
                $votedByUser = in_array($poll->poll_option_id, $userVotes, true);

                $summary['options'][$poll->poll_option_id] = [
                    'text' => $poll->poll_option_text,
                    'total' => $poll->poll_option_total,
                    'voted_by_user' => $votedByUser,
                ];

                $summary['total'] += $poll->poll_option_total;
                $summary['user_votes'] += $votedByUser ? 1 : 0;
            }
        }

        return $summary;
    }
}
