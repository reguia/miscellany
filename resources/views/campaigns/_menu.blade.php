<?php /** @var App\Models\Campaign $campaign */ ?>
<div class="box box-solid">
    <div class="box-body box-profile">
        @if ($campaign->image)
            <div class="full-sized-image" style="background-image: url('{{ Storage::url($campaign->image) }}');">
                <h1>{!! $campaign->name !!}</h1>
            </div>
        @else
            <h1 class="profile-username text-center">{!! $campaign->name !!}</h1>
        @endif

        <ul class="list-group list-group-unbordered">
            <li class="list-group-item">
                <b>{{ trans('campaigns.fields.visibility') }}</b>
                <span  class="pull-right">
                    {{ trans('campaigns.visibilities.' . $campaign->visibility) }}
                </span>
                <br class="clear" />
            </li>
            @if (false && $campaign->isPublic())
                <li class="list-group-item">
                    <b>{{ trans('campaigns.fields.followers') }}</b>
                    <span  class="pull-right">
                    {{ $campaign->followers->count() }}
                </span>
                    <br class="clear" />
                </li>
            @endif
            @if ($campaign->locale)
            <li class="list-group-item">
                <b>{{ trans('campaigns.fields.locale') }}</b>
                <span  class="pull-right">
                    {{ trans('languages.codes.' . $campaign->locale) }}
                </span>
                <br class="clear" />
            </li>
            @endif
            <li class="list-group-item">
                <b>{{ trans('campaigns.fields.entity_count') }}</b>
                <span  class="pull-right">
                    {{ number_format($campaign->entities()->count()) }}
                </span>
                <br class="clear" />
            </li>
            @if (!empty($campaign->system))
            <li class="list-group-item">
                <b>{{ trans('campaigns.fields.system') }}</b>
                <span  class="pull-right">
                    {{ $campaign->system }}
                </span>
                <br class="clear" />
            </li>
            @endif
            @if ($campaign->boosted())
                <li class="list-group-item text-maroon">
                    <b><i class="fa fa-rocket"></i> {{ __('campaigns.fields.boosted') }}</b>
                    <span class="pull-right">
                        {{ $campaign->boosts->first()->user->name }}
                    </span>
                </li>
            @endif
        </ul>

        @if (!$campaign->boosted())
            <a href="{{ route('settings.boost', ['campaign' => $campaign->id]) }}" class="btn btn-block bg-maroon btn-boost">
                <i class="fa fa-rocket"></i> {{ __('campaigns.show.actions.boost') }}
            </a>
        @endif
        @can('update', $campaign)
            <a href="{{ route('campaigns.edit', $campaign->id) }}" class="btn btn-primary btn-block">
                <i class="fa fa-edit" aria-hidden="true"></i> {{ trans('crud.update') }}
            </a>
        @endcan

        @can('leave', $campaign)
            <button data-url="{{ route('campaigns.leave', $campaign->id) }}" class="btn btn-warning btn-block click-confirm" data-toggle="modal" data-target="#click-confirm" data-message="{{ trans('campaigns.leave.confirm', ['name' => $campaign->name]) }}">
                <i class="fa fa-sign-out-alt" aria-hidden="true"></i> {{ trans('campaigns.show.actions.leave') }}
            </button>
        @endcan


        @can('delete', $campaign)
            <button class="btn btn-block btn-danger delete-confirm" data-name="{{ $campaign->name }}" data-toggle="modal" data-target="#delete-confirm">
                <i class="fa fa-trash" aria-hidden="true"></i> {{ trans('crud.remove') }}
            </button>
            {!! Form::open(['method' => 'DELETE','route' => ['campaigns.destroy', $campaign->id], 'style'=>'display:inline', 'id' => 'delete-confirm-form']) !!}
            {!! Form::close() !!}
            {!! Form::close() !!}
        @endcan
    </div>
</div>

@if (!auth()->guest() and $campaign->userIsMember())
<div class="box box-solid">
    <div class="box-header with-border">
        <h3 class="box-title">
            {{ __('campaigns.show.tabs.menu') }}
        </h3>
    </div>
    <div class="box-body no-padding">
        <ul class="nav nav-pills nav-stacked">
            <li class="@if(empty($active))active @endif">
                <a href="{{ route('campaign') }}">
                    {{ __('crud.panels.entry') }}
                </a>
            </li>
            <li class="@if(!empty($active) && $active == 'users')active @endif">
                <a href="{{ route('campaign_users.index') }}">
                    {{ __('campaigns.show.tabs.members') }}
                    <span class="label label-default pull-right">
                        {{ $campaign->users()->count() }}
                    </span>
                </a>
            </li>
            @can('update', $campaign)
            <li class="@if(!empty($active) && $active == 'roles')active @endif">
                <a href="{{ route('campaign_roles.index') }}">
                    {{ __('campaigns.show.tabs.roles') }}
                    <span class="label label-default pull-right">
                        {{ $campaign->roles()->count() }}
                    </span>
                </a>
            </li>
            <li class="@if(!empty($active) && $active == 'settings')active @endif">
                <a href="{{ route('campaign_settings') }}">
                    {{ __('campaigns.show.tabs.settings') }}
                    <span class="label label-default pull-right">
                        {{ $campaign->setting->countEnabledModules() }}
                    </span>
                </a>
            </li>
            <li class="@if(!empty($active) && $active == 'export')active @endif">
                <a href="{{ route('campaign_export') }}">
                    {{ __('campaigns.show.tabs.export') }}
                </a>
            </li>
            @endcan
        </ul>
    </div>
</div>
@endif
