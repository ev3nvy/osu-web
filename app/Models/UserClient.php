<?php

/**
 *    Copyright 2015-2018 ppy Pty. Ltd.
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

namespace App\Models;

class UserClient extends Model
{
    const CREATED_AT = 'timestamp';

    protected $table = 'osu_user_security';

    protected $dates = ['timestamp'];

    public $timestamps = false;

    public function build()
    {
        return $this->belongsTo(Build::class, 'osu_md5', 'hash');
    }

    public function isLatest()
    {
        if ($this->build === null) {
            return false;
        }

        $latestBuildId = Build::select('build_id')
            ->where([
                'test_build' => false,
                'stream_id' => $this->build->stream_id,
            ])->last()
            ->getKey();

        return $this->build->getKey() === $latestBuildId;
    }
}
