<section itemtype="http://schema.org/ProfilePage" itemscope>
    <div itemprop="about" itemscope itemtype="http://schema.org/Person">
        <div class="row" style="display:inline-block;">
            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                <img itemprop="image" src="{{ \Helpers\Functions\Users::getGravatar($user) }}" class="img-circle" alt="User Image"/>
            </div>
            <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9" style="vertical-align: middle;">
                <h2 style="margin: 0 0 0 0.5em;word-wrap: break-word;white-space: normal;">
                    <span itemprop="name" class="user-name">{{ $user->fullName() }}</span>
                </h2>
                <h3 style="margin: 0 0 0 0.5em;word-wrap: break-word;white-space: normal;"><small>(<em itemprop="additionalName" class="user-nick">{{ $user->user_name }}</em>)</small></h3>
            </div>
        </div>
        <div class="vcard-details row">
            @if(!empty(Auth::user()) && (Auth::user() == $user || Auth::user()->isAnAdmin()))
            <dl title="Email" style="margin-bottom: 0em">
                <dd style="line-height: 3em; vertical-align: middle;">
                    <i class="fa fa-bitcoin circle-letter" style="margin-right: 0.5em;"></i>
                    User Email: <a class="email" data-email="{{ $user->email }}" href="mailto:{{ $user->email }}">{{ $user->email }}</a>
                </dd>
            </dl>
            @endif
            <dl title="List of Faucets">
                <dd style="line-height: 3em; vertical-align: middle;">
                    <i class="fa fa-bitcoin circle-letter" style="margin-right: 0.5em;"></i>
                    {!!
                        link_to_route(
                            'users.faucets',
                            $user->user_name . "'s Faucets",
                            ['userSlug' => $user->slug],
                            ['itemprop' => 'url', 'target' => '_blank', 'title' => $user->user_name . "'s Faucets."]
                        )
                    !!}
                    <small>(in a new page)</small>
                    {!! Html::decode(
                            link_to_route(
                                'users.faucets',
                                '<i class="fa fa-external-link" style="color: #3c8dbc;"></i>',
                                ['userSlug' => $user->slug],
                                ['itemprop' => 'url', 'target' => '_blank', 'title' => $user->user_name . "'s Faucets."]
                            )
                        )
                    !!}
                </dd>
            </dl>
            <dl title="List of Faucets, Organised by Payment Processors" style="margin-top: -1.5em;">
                <dd style="line-height: 3em; vertical-align: middle;">
                    <i class="fa fa-bitcoin circle-letter" style="margin-right: 0.5em;"></i>
                    {!!
                        link_to_route(
                            'users.payment-processors',
                            $user->user_name . "'s Faucets",
                            ['userSlug' => $user->slug],
                            [
                                'itemprop' => 'url', 'target' => '_blank',
                                'title' => $user->user_name . "'s Faucets, organised by payment processors."
                            ]
                        )
                    !!} <small>(organised by payment processors, in a new page)</small>
                    {!! Html::decode(
                            link_to_route(
                            'users.payment-processors',
                            '<i class="fa fa-external-link" style="color: #3c8dbc;"></i>',
                            ['userSlug' => $user->slug],
                            [
                                'itemprop' => 'url', 'target' => '_blank',
                                'title' => $user->user_name . "'s Faucets, organised by payment processors."
                            ]
                        )
                        )
                    !!}
                </dd>
            </dl>
            <dl title="Bitcoin Address" style="margin-top: -1.5em;">
                <dd style="line-height: 3em; vertical-align: middle;">
                    <i class="fa fa-bitcoin circle-letter" style="margin-right: 0.5em;"></i>
                    <a
                        href="https://blockchain.info/address/{{ $user->bitcoin_address }}"
                        target="_blank" title="{{ $user->user_name }}'s Bitcoin Address"
                    >Bitcoin Address</a>
                    <a
                        href="https://blockchain.info/address/{{ $user->bitcoin_address }}"
                        target="_blank" title="{{ $user->user_name }}'s Bitcoin Address"
                    ><i class="fa fa-external-link" style="color: #3c8dbc;"></i></a>
                </dd>
            </dl>
            <dl title="Registered Date" style="margin-top: -1.5em;">
                <dd style="line-height: 3em; vertical-align: middle;">
                    <i class="fa fa-bitcoin circle-letter" style="margin-right: 0.5em;"></i>
                    Joined on {{ date('l jS \of F Y\, \a\t H:i:s A T', strtotime($user->created_at)) }}
                </dd>
            </dl>
            <dl title="Profile Last Updated" style="margin-top: -1.5em;">
                <dd style="line-height: 3em; vertical-align: middle;">
                    <i class="fa fa-bitcoin circle-letter" style="margin-right: 0.5em;"></i>
                    Updated profile on {{ date('l jS \of F Y\, \a\t H:i:s A T', strtotime($user->updated_at)) }}
                </dd>
            </dl>
            @if(!empty(Auth::user()) && Auth::user()->isAnAdmin())
                <p>{{ $user->user_name }} is
                    @if(!$user->isAnAdmin())
                        not
                    @else
                        an
                    @endif admin/owner.</p>
                <dl title="User Roles">
                    <p><i class="fa fa-bitcoin circle-letter" style="margin-right: 0.5em;"></i>User Roles: </p>
                    <dd class="roles">
                        <ul>
                        @if(!empty($user))
                            @foreach ($user->roles()->get() as $role)
                                <li>{!! ucfirst($role->name) !!}</li>
                            @endforeach
                        @endif
                        </ul>
                    </dd>
                </dl>
                <dl title="User Privileges">
                    <p><i class="fa fa-bitcoin circle-letter" style="margin-right: 0.5em;"></i>User Privileges: </p>
                    @if(!empty($user) && count($user->permissions) > 0)
                        <dd class="privileges">
                            <ul>
                                @foreach($user->permissions as $permission)
                                    <li>{!! $permission->display_name !!}</li>
                                @endforeach
                            </ul>
                        </dd>
                    @endif
                </dl>
            @endif
        </div>
    </div>
</section>